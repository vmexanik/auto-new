<?

/**
 * @author 
 */

class AManagerPanelManagerNewOrders extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
		require(SERVER_PATH.'/include/order_status_config.php');
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$aRequest['change_top_menu']=1;
		$this->PreIndex ();
		Base::$tpl->assign('sAction',Base::$aRequest['action']);
		
		$oTable=new Table();
		
		if (!Base::$aRequest['search']) Base::$aRequest['search']=array();
		
		if (!Base::$aRequest['sort'])
		    Base::$aRequest['sort'] = 'id';
		
		if (!Base::$aRequest['way'])
		    Base::$aRequest['way'] = 'down';

		// rewrite sort
		$sSort = Base::$aRequest['sort'];
		if ($sSort == 'created')
			$sSort = "cp.post_date";
		elseif ($sSort == 'order_status')
			$sSort = "cp.order_status";
		elseif ($sSort == 'customer_name')
			$sSort = "uc.name";
		elseif ($sSort == 'name_manager')
			$sSort = "um.name";
		elseif ($sSort == 'changed')
			$sSort = "cp.post_date_changed";
		elseif ($sSort=='profit') // TODO
			$sSort = "1";
		
		$sWhere.=" and cp.is_internet_order='1' and cp.id_manager='0' ";
		
		$oTable->aDataFoTable=Db::getAll(Base::GetSql("CartPackage",array(
			"where"=>$sWhere,
		))." order by ".$sSort." ".(Base::$aRequest['way']=='down' ? 'desc' : ''));
		$oTable->sType = 'array';
		
		$oTable->aCallback=array($this,'CallParse');
		$oTable->aCallbackAfter=array($this,'CallParseOrder');

		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'#CP'),
 			'post_date'=>array('sTitle'=>'date'),
			'order_status'=>array('sTitle'=>'order_status'),
			'delivery_type_name'=>array('sTitle'=>'delivery'),
 			'customer_name'=>array('sTitle'=>'customer_name'),
		    'assigned_manager'=>array('sTitle'=>'assigned_manager'),
			'price_total'=>array('sTitle'=>'price_total'),
			'nova_ttn'=>array('sTitle'=>'nova_ttn'),
			'action'=>array('nosort' => 1),
		);
		$oTable->iRowPerPage=20;
		$oTable->sDataTemplate='manager_panel/manager_package_list/row_order.tpl';
		$oTable->bCheckVisible=false;
		$oTable->sSubtotalTemplate='manager_panel/manager_package_list/subtotal_order.tpl';
		$oTable->bStepperVisible=true;
		$oTable->sClass="table table-striped";
		$oTable->bAjaxStepper = true;
		$oTable->bFormAvailable = false;
		$oTable->sQueryString = urlencode(Base::$aRequest['return']);
		// macro sort table
		ManagerPanel::SortTableMP();

		Base::$sText.=$oTable->getTable();

		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem) {
	    if (!$aItem)
	        return;
	
	    $aIdCP=array();
	    $dOrderSubtotal = 0;
	    $dOrderSubtotalProfit = 0;
	    foreach ($aItem as $iKey => $aValue) {
	        $aIdCP[$aValue['id']] = $aValue['id'];
	        $dOrderSubtotal += Currency::PrintPrice($aValue['price_total']);
	    }
	    Base::$tpl->assign('dOrderSubtotal',$dOrderSubtotal);
	    $aProfitAssoc = Db::getAssoc("SELECT id_cart_package,sum( price_original_one_currency * number )
			FROM `cart`	where cart.id_cart_package in (".implode(',',$aIdCP).") and order_status!='refused' GROUP BY id_cart_package");
	    $aTmp=array();
	    $aTmpSort=array();
	    foreach ($aItem as $iKey => $aValue) {
	        $aItem[$iKey]['profit'] = $aItem[$iKey]['price_total']-$aProfitAssoc[$aValue['id']];
	        $dOrderSubtotalProfit += $aItem[$iKey]['profit'];
	        if (Base::$aRequest['sort']=='profit') {
	            $aTmp[]=$aItem[$iKey];
	            $aTmpSort[] = $aItem[$iKey]['profit'];
	        }
	    }
	    Base::$tpl->assign('dOrderSubtotalProfit',$dOrderSubtotalProfit);
	    Base::$tpl->assign('dOrderCntTotal',count($aItem));
	    if (Base::$aRequest['sort']=='profit') {
	        if (!Base::$aRequest['way'] || Base::$aRequest['way']!='down')
	            array_multisort ($aTmpSort, SORT_ASC, SORT_NUMERIC, $aTmp);
	        else
	            array_multisort ($aTmpSort, SORT_DESC, SORT_NUMERIC, $aTmp);
	        $aItem = $aTmp;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseOrder(&$aItem) {
	    if (!$aItem)
	        return;
	    $aIdCart=array();
	    $aIdUser=array();
	    foreach ($aItem as $iKey => $aValue) {
	        $aItem[$iKey]['order_status_select'] = $this->BuildStatusOrder($aValue['order_status'],$aValue);
	        $aIdCart[$aValue['id']] = $aValue['id'];
	        $aIdUser[$aValue['id_user']] = $aValue['id_user'];
	        $aData = parse_timestamp($aItem[$iKey]['created']);
	        $sDiff = '';
	        if ($aData['month'] && $aData['year']) {
	            $sDiff = date("d/m/Y H:i",strtotime($aItem[$iKey]['post_date']));
	        }
	        elseif ($aData['month'] && !$aData['year']) {
	            $sDiff = date("d/m H:i",strtotime($aItem[$iKey]['post_date']));
	        }
	        elseif ($aData['day']) {
	            if ($aData['day']==1)
	                $sDiff = 'Вчера, '.date("H:i",strtotime($aItem[$iKey]['post_date']));
	            else
	                $sDiff = date("d/m H:i",strtotime($aItem[$iKey]['post_date']));
	        }
	        else {
	            if ($aData['hour'])
	                $sDiff .= $aData['hour'].'ч ';
	            if ($aData['min'])
	                $sDiff .= $aData['min'].'м ';
	        }
	        $aItem[$iKey]['created'] = $sDiff;
	    }
	    $aMaxChangeDate = Db::getAssoc("SELECT id_cart_package, max(post_date) as post_date,unix_timestamp(now())-unix_timestamp(max(post_date)) as mody  FROM `cart_package_log`
			WHERE id_cart_package IN (".implode(",",$aIdCart).") GROUP BY id_cart_package");
	    $aCountOrdersUser = Db::getAssoc("SELECT id_user, count(id) as cnt FROM `cart_package`
			WHERE id_user IN (".implode(",",$aIdUser).") GROUP BY id_user");
	    foreach ($aItem as $iKey => $aValue) {
	        $aItem[$iKey]['user_is_new'] = ($aCountOrdersUser[$aValue['id_user']] <= 1 ? 1 : 0);
	        if ($aValue['changed']!='0000-00-00 00:00:00') {
	            $iChanged = $aValue['changed'];
	            $sChanged = $aValue['post_date_changed'];
	        }
	        elseif ($aMaxChangeDate[$aValue['id']]['mody']) {
	            $iChanged = $aMaxChangeDate[$aValue['id']]['mody'];
	            $sChanged = $aMaxChangeDate[$aValue['id']]['post_date'];
	        }
	        else
	            continue;
	        	
	        $aData = parse_timestamp($iChanged);
	        $sDiff = '';
	        if ($aData['month'] && $aData['year']) {
	            $sDiff = date("d/m/Y H:i",strtotime());
	        }
	        elseif($aData['month'] && !$aData['year']) {
	            $sDiff = date("d/m H:i",strtotime($sChanged));
	        }
	        elseif ($aData['day']) {
	            if ($aData['day']==1)
	                $sDiff = 'Вчера, '.date("H:i",strtotime($sChanged));
	            else
	                $sDiff = date("d/m H:i",strtotime($sChanged));
	        }
	        else {
	            if ($aData['hour'])
	                $sDiff .= $aData['hour'].'ч ';
	            if ($aData['min'])
	                $sDiff .= $aData['min'].'м ';
	        }
	        $aItem[$iKey]['changed'] = $sDiff;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function BuildStatusOrder($sOrderStatus,$aData=array()) {
	    static $aListStatus;
	
	    if (!$aListStatus) {
	        $aListStatus = array(
	            "return",
                "refused",
                "pending",
                "work",
                "assembled",
                "cancel_customer",
                "new",
                "end",
                "cover",
                "need_delivery",
                'shipment',
                'in_wait',
	        );
	    }
	
	    // 		$aStoreOrderStatus = Base::$tpl->get_template_vars('aStoreOrderStatus');
	    // 		if (in_array($sOrderStatus,$aStoreOrderStatus) || $sOrderStatus=='assembled')
	        // 			$aListStatus = $aStoreOrderStatus;
	
	        $sStyle = $sOrderStatus;
	        $sOptions='';
	        foreach ($aListStatus as $sStatus) {
	            if ($sStatus!=$sOrderStatus)
	                $sOptions .= "<option value='".$sStatus."'>".Language::getMessage('status_'.$sStatus)."</option>";
	        }
	        return "<select class='selectpicker' data-style='btn-".$sStyle."'
					onchange=\"xajax_process_browse_url('/?action=manager_panel_manager_package_list_change_order_status&amp;id=".$aData['id']."&amp;row_order=1&amp;order_status_old=".$sOrderStatus."&amp;order_status='+this.options[this.selectedIndex].value ); return false;\">
					<option value='".$sOrderStatus."' selected>".Language::getMessage('status_'.$sOrderStatus)."</option>
					".$sOptions."
				</select>";
	}
	//-----------------------------------------------------------------------------------------------
	public function CatchMe() {
	    if(Base::$aRequest['id']) {
    	    Db::Execute("update cart_package set
    	        is_internet_order='1',
    	        id_manager='".Auth::$aUser['id']."'
    	        where id='".Base::$aRequest['id']."'
            ");
	    }
	    parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
}
function parse_timestamp( $t = 0 )
{
	$year = floor( $t / 31536000 );
	$month = floor( $t / 2592000 );
	$day = ( $t / 86400 ) % 30;
	$hour = ( $t / 3600 ) % 24;
	$min = ( $t / 60 ) % 60;
	$sec = $t % 60;

	return array( 'month' => $month, 'day' => $day, 'hour' => $hour, 'min' => $min, 'sec' => $sec );
}
?>