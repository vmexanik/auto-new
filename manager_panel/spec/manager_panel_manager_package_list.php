<?

/**
 * @author Vladimir Fedorov
 */

class AManagerPanelManagerPackageList extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
		require(SERVER_PATH.'/include/order_status_config.php');
		Base::$tpl->assign('aPurchaseDetailOrderStatus',array("" => "")+$aPurchaseDetailOrderStatus);
		Base::$tpl->assign('aStoreOrderStatus',$aStoreOrderStatus);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$aRequest['change_top_menu']=1;
		$this->PreIndex ();

		Base::$aRequest['return'] = 'action='.Base::$aRequest['action'];
		Base::$tpl->assign('sAction',Base::$aRequest['action']);
		Base::$aRequest['return'] = urlencode(Base::$aRequest['return']);
		
		if (Base::$aRequest['search_order_status'] && Base::$aRequest['search_order_status']!='0') {
			$bCartJoin=true;
			Base::$tpl->assign('search_order_status',Base::$aRequest['search_order_status']);
		}
		if (!Base::$aRequest['sort'])
			Base::$aRequest['sort'] = 'id';
		
		if (!Base::$aRequest['way'])
			Base::$aRequest['way'] = 'down';
		
		if (Base::$aRequest['search_order_status']) {
			$sWhere.=" and cp.order_status = '".Base::$aRequest['search_order_status']."'";
			Base::$aRequest['return'] .= "&search_order_status=".Base::$aRequest['search_order_status'];
			$_SESSION['search']['search_order_status'] = Base::$aRequest['search_order_status'];
		}
		else
			unset($_SESSION['search']['search_order_status']);

		// from view order
		if (Base::$aRequest['search_id_user']) {
			$sWhere.=" and cp.id_user=".Base::$aRequest['search_id_user'];
			$_SESSION['search']['id_user'] = Base::$aRequest['search_id_user'];
			Base::$tpl->assign('filtered',1);
		}
		else {
			unset($_SESSION['search']['id_user']);
		
			if (!Base::$aRequest['is_popup']) {
				if (!Base::$aRequest['search_all_manager']) {
					$sWhere.=" and cp.id_manager=".Auth::$aUser['id_user'];
					unset($_SESSION['search']['search_all_manager']);
				}
				else {
					Base::$tpl->assign('search_all_manager',1);
					Base::$aRequest['return'] .= "&search_all_manager=1";
					$_SESSION['search']['search_all_manager'] = 1;
				}
			}
			// find popup
			if (Base::$aRequest['is_popup']) {
				if (Base::$aRequest['search']['manager']) {
					$sWhere.=" and cp.id_manager=".Base::$aRequest['search']['manager'];
					Base::$tpl->assign('search_manager',Base::$aRequest['search']['manager']);
					Base::$aRequest['return'] .= "&search[manager]=".Base::$aRequest['search']['manager'];
					$_SESSION['search']['manager'] = Base::$aRequest['search']['manager'];
				}
				else 
					unset($_SESSION['search']['manager']);
				
				if (Base::$aRequest['search']['phone']) {
					$sWhere.=" and uc.phone='".Base::$aRequest['search']['phone']."'";
					Base::$tpl->assign('search_phone',Base::$aRequest['search']['phone']);
					Base::$aRequest['return'] .= "&search[phone]=".Base::$aRequest['search']['phone'];
					$_SESSION['search']['phone'] = Base::$aRequest['search']['phone'];
				}
				else
					unset($_SESSION['search']['phone']);
					
				Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
				if (Base::$aRequest['search']['phone'] || Base::$aRequest['search']['manager'])
					Base::$tpl->assign('filtered',1);
			}
		}
		// --------------
		Base::$sText.=Base::$tpl->fetch('manager_panel/manager_package_list/form_order_search.tpl');
		
		$oTable=new Table();
		
		/*require(SERVER_PATH.'/include/order_status_config.php');
		Base::$tpl->assign('aOrderStatus',array_keys($aOrderStatusConfig));
		Base::$tpl->assign('aAllowChangeProviderDetailStatus',$aAllowChangeProviderDetailStatus);
		Base::Message();*/
		
		if (!Base::$aRequest['search']) Base::$aRequest['search']=array();

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
		
		$oTable->aDataFoTable=Db::getAll(Base::GetSql("CartPackage",Base::$aRequest['search']+array(
			"where"=>$sWhere,
		))." order by ".$sSort." ".(Base::$aRequest['way']=='down' ? 'desc' : ''));
		$oTable->sType = 'array';
		
		//$oTable->aOrdered="order by ".$sSort." ".(Base::$aRequest['way']=='down' ? 'desc' : '');
		$oTable->aCallback=array($this,'CallParse');
		
		$oTable->aCallbackAfter=array($this,'CallParseOrder');

		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'#CP'),
 			'order_status'=>array('sTitle'=>'order status'),
			'delivery_type_name'=>array('sTitle'=>'delivery type'),
 			'customer_name'=>array('sTitle'=>'man_User'),
			'name_manager'=>array('sTitle'=>'manager'),
			'price_total'=>array('sTitle'=>'Total'),
			'profit'=>array('sTitle'=>'profit'),
			'created'=>array('sTitle'=>'created'),
			'changed'=>array('sTitle'=>'changed'),
			'action'=>array('nosort' => 1),
		);
		$oTable->iRowPerPage=10;
		$oTable->sDataTemplate='manager_panel/manager_package_list/row_order.tpl';
		//$oTable->sButtonTemplate='manager/button_order.tpl';
		//$oTable->bCheckVisible=true;
		$oTable->sSubtotalTemplate='manager_panel/manager_package_list/subtotal_order.tpl';
		$oTable->bStepperVisible=true;
		$oTable->sClass="table table-striped";
		$oTable->bAjaxStepper = true;
		$oTable->sQueryString = Base::$sServerQueryString;
		//$this->SetDefaultTable($oTable);

		// macro sort table
		ManagerPanel::SortTableMP();

		Base::$sText.=$oTable->getTable();

		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply()
	{
		if (date('Y-m-d', strtotime(Base::$aRequest['data']['post_date'])) != '1970-01-01')
		    Base::$aRequest['data']['post_date'] = date('Y-m-d', strtotime(Base::$aRequest['data']['post_date']));
		else
		    Base::$aRequest['data']['post_date'] = '';
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		if (!$aData['post_date']) $iTime=time();
		else $iTime=strtotime($aData['post_date']);

		$aData['post_date'] = date(Base::GetConstant('date_format:post_date'),$iTime);
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
	public function FinanceAdd()
	{
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').slideToggle();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function BuildStatusOrder($sOrderStatus,$aData=array()) {
		static $aListStatus;
		
		if (!$aListStatus)
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
                'in_wait',);
		
//		$aStoreOrderStatus = Base::$tpl->get_template_vars('aStoreOrderStatus');
//		if (in_array($sOrderStatus,$aStoreOrderStatus) || $sOrderStatus=='assembled')
//			$aListStatus = $aStoreOrderStatus;
		
		$sStyle = $sOrderStatus;
		$sOptions='';
		foreach ($aListStatus as $sStatus) {
			if ($sStatus!=$sOrderStatus)
				$sOptions .= "<option value='".$sStatus."'>".Language::getMessage('status_'.$sStatus)."</option>";
		}
		return "<select class='selectpicker' data-style='btn-".$sStyle."' 
					onchange=\"xajax_process_browse_url('/?action=manager_panel_manager_package_list_change_order_status&id=".$aData['id'].
						"&row_order=1&order_status_old=".$sOrderStatus."&order_status='+this.options[this.selectedIndex].value ); return false;\">
					<option value='".$sOrderStatus."' selected>".Language::getMessage('status_'.$sOrderStatus)."</option>
					".$sOptions."
				</select>";
	}
	//-----------------------------------------------------------------------------------------------
    public function ChangeOrderStatus() {
        if(Base::$aRequest['id'] && Base::$aRequest['order_status']) {
            if(Base::$aRequest['order_status']=='end' && Auth::$aUser['is_super_manager']==0) {
                Base::$oResponse->AddAlert("Операция не позволена!");
                Base::$aRequest['order_status']=Base::$aRequest['order_status_old'];
            } else {
                Db::Execute("update cart_package set order_status='".trim(Base::$aRequest['order_status'])."' where id='".Base::$aRequest['id']."' ");
            }
        }
        if(Base::$aRequest['row_order']==1) {
            Base::$oResponse->AddAssign('order_status_select_block_'.Base::$aRequest['id'],'innerHTML',$this->BuildStatusOrder(Base::$aRequest['order_status'],array('id'=>Base::$aRequest['id'])) );
        }
    }
	//-----------------------------------------------------------------------------------------------
	public function ChangeStatus() {
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect();
			return;
		}

		$aCartPackage = Db::GetRow("Select * from cart_package where id=".Base::$aRequest['id']);
		if ($aCartPackage && $aCartPackage['order_status']!=Base::$aRequest['status']) {
			Db::Execute("update cart_package set order_status='".Base::$aRequest['status']."' where id='".$aCartPackage['id']."'");
			// log
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$aCartPackage['id']."','".Auth::$aUser['id_user']."','".date("Y-m-d H:i:s")."','".Base::$aRequest['status']."','','".Auth::GetIp()."')");
		}
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function Search()
	{
		Base::$tpl->assign('aUserManager',array(""=>"")+Base::$db->GetAssoc("select id, login as name
			from user where type_='manager' and visible=1"));
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('search order') );
		if ($_SESSION['search']['phone'])
			Base::$tpl->assign('search_phone',$_SESSION['search']['phone']);
		if ($_SESSION['search']['manager'])
			Base::$tpl->assign('search_manager',$_SESSION['search']['manager']);
		
		$sBody = Base::$tpl->fetch('manager_panel/manager_package_list/form_search_popup.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'500','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('#user_phone').mask(\"(099)999-99-99\",{placeholder:\"_\"});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').slideToggle();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function View($sReturn='',$sType='view',$sDataTemplate='',$sTableTemplate='')
	{
		if (!Base::$aRequest['id'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$aCartPackage['id_user'])));
		if (!$aUser)
			return;
		
		Base::$tpl->assign('aUser',$aUser);
		$sBalance = Base::$tpl->fetch('manager_panel/template/balance.tpl');
		Base::$tpl->assign('sBalance',$sBalance);
		
		//---------------------------------------------------------------------
		if (!$sReturn)
			Base::$aRequest['return'] = 'action=manager_panel_manager_package_list_view&id='.Base::$aRequest['id'];
		else 
			Base::$aRequest['return'] = $sReturn;
		
		if (!$sDataTemplate)
			$sDataTemplate = 'manager_panel/manager_package_list/row_order_items.tpl';
		
		if (!$sTableTemplate)
			$sTableTemplate = 'manager_panel/manager_package_list/form_package_view.tpl';
		//---------------------------------------------------------------------
		
		Base::$aRequest['status_order'] = $aCartPackage['order_status'];
		
		$aCartPackage['order_status_select'] = AManagerPanelManagerPackageList::BuildStatusOrder($aCartPackage['order_status'],$aCartPackage);
		$aCartPackage['change'] = $aCartPackage['post_date'];
		$sChange = Db::getOne("Select max(post_date) from cart_package_log where id_cart_package=".$aCartPackage['id']);
		if ($sChange && $sChange!=$aCartPackage['change'])
			$aCartPackage['change'] = $sChange;
		
		$aCartPackage['price_total_no_delivery'] = Currency::PrintPrice($aCartPackage['price_total'],0,0,'<none>')-$aCartPackage['price_delivery'];
		Base::$tpl->assign('aCartPackage',$aCartPackage);
		
		$aLogComment = Db::getAll("Select l.*,um.name,u.login,unix_timestamp(now())-unix_timestamp(l.post_date) as mody 
			from cart_package_manager_comment_log l
			left join user_manager um on um.id_user = l.id_user_manager
			left join user u on u.id = l.id_user_manager
			where l.id_cart_package=".$aCartPackage['id']." order by l.post_date desc");
		if ($aLogComment) {
			foreach ($aLogComment as $iKey => $aVal) {
				$aData = parse_timestamp($aVal['mody']);
				$sDiff = '';
				if ($aData['month'] && $aData['year']) {
					$sDiff = date("d/m/Y H:i",strtotime($aVal['post_date']));
				}
				elseif ($aData['month'] && !$aData['year']) {
					$sDiff = date("d/m H:i",strtotime($aVal['post_date']));
				}
				elseif ($aData['day']) {
					if ($aData['day']==1)
						$sDiff = 'Вчера, '.date("H:i",strtotime($aVal['post_date']));
					else
						$sDiff = date("d/m H:i",strtotime($aVal['post_date']));
				}
				else {
					if ($aData['hour'])
						$sDiff .= $aData['hour'].'ч ';
					if ($aData['min'])
						$sDiff .= $aData['min'].'м ';
				}
				$aLogComment[$iKey]['created'] = $sDiff;
			}				
			Base::$tpl->assign('aLogComment',$aLogComment);
			$sCommentLog = Base::$tpl->fetch('manager_panel/manager_package_list/package_comment_log.tpl');
			Base::$tpl->assign('sCommentLog',$sCommentLog);
		}

		$oManagerPanelManagerPackageList  = new AManagerPanelManagerPackageList();
		
		// refused
		$oTable=new Table();
		$oTable->aDataFoTable=Db::getAll("Select c.*, cat.title as brand 
			from cart c
			inner join cat on cat.pref = c.pref 
			where c.id_cart_package=".$aCartPackage['id']." and order_status ='refused' order by c.name_translate");
		$oTable->sType = 'array';		
		$oTable->aColumn=array(
				'brand'=>array('sTitle'=>'cat','nosort' => 1),
				'code'=>array('sTitle'=>'article','nosort' => 1),
				'name_translate'=>array('sTitle'=>'name','nosort' => 1),
				'number'=>array('sTitle'=>'cnt','nosort' => 1),
				'term'=>array('sTitle'=>'term','nosort' => 1),
				'price'=>array('sTitle'=>'price','nosort' => 1),
				'price_total'=>array('sTitle'=>'total','nosort' => 1),
				'provider_name'=>array('sTitle'=>'provider','nosort' => 1),
		);
		$oTable->iRowPerPage=10000;
		$oTable->aCallbackAfter=array($oManagerPanelManagerPackageList,'CallParseOrderItemsRefused');
		$oTable->sDataTemplate='manager_panel/manager_package_list/row_order_items.tpl';
		$oTable->bStepperVisible=false;
		$oTable->sClass="table table-striped";
		$oTable->sQueryString = Base::$sServerQueryString;

		$sTableRefused = $oTable->getTable('Refused items order');
		if (Base::$aRequest['item_refused'])
			Base::$tpl->assign('sOrderItemsRefused',$sTableRefused);
		
		$oTable=new Table();
		$oTable->aDataFoTable=Db::getAll("Select c.*, cat.title as brand
			from cart c
			inner join cat on cat.pref = c.pref
			where c.id_cart_package=".$aCartPackage['id']." and order_status !='refused' order by c.name_translate");
		$oTable->sType = 'array';
		
		$oTable->aColumn=array(
				'brand'=>array('sTitle'=>'cat','nosort' => 1),
				'code'=>array('sTitle'=>'article','nosort' => 1),
				'name_translate'=>array('sTitle'=>'name','nosort' => 1),
				'number'=>array('sTitle'=>'cnt','nosort' => 1),
				'term'=>array('sTitle'=>'term','nosort' => 1),
				'price'=>array('sTitle'=>'price','nosort' => 1),
				'price_total'=>array('sTitle'=>'total','nosort' => 1),
				'provider_name'=>array('sTitle'=>'provider','nosort' => 1),
				//'action'=>array('nosort' => 1),
		);
		if ($sType=='edit')
			$oTable->aColumn['action'] = array('nosort' => 1);
		
		$oTable->iRowPerPage=10000;
		$oTable->aCallbackAfter=array($oManagerPanelManagerPackageList,'CallParseOrderItems');
		$oTable->sDataTemplate=$sDataTemplate;
		$oTable->bStepperVisible=false;
		if ($sType=='split') {
			$oTable->bCheckAllVisible=true;
			$oTable->bCheckVisible=true;
			$oTable->bDefaultChecked=false;
		}
		$oTable->sClass="table table-striped";
		$oTable->sQueryString = Base::$sServerQueryString;
		
		Base::$tpl->assign('sOrderItems',$oTable->getTable());
		
		$sReturn = 'action='.Base::$aRequest['action'].'&id='.$aCartPackage['id'];
		Base::$tpl->assign('sReturn',urlencode($sReturn));
		
		Base::$sText .= Base::$tpl->fetch($sTableTemplate);
		//Base::$aRequest['not_change_top_menu']=1;
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewLog()
	{
		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list';
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage = Db::GetRow("Select * from cart_package where id=".Base::$aRequest['id']);
		if (!$aCartPackage) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('history package log').' #'.$aCartPackage['id']);
		
		$aLog = Db::getAll("Select l.*,um.name,u.login from cart_package_log l
			left join user_manager um on um.id_user = l.id_user_manager
			left join user u on u.id = l.id_user_manager 
			where l.id_cart_package=".$aCartPackage['id']." order by post_date desc");
		
		Base::$tpl->assign('aLog',$aLog);
		$sBody = Base::$tpl->fetch('manager_panel/manager_package_list/package_view_log.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({width:'750',overflow:'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').slideToggle();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetPackageOwn()
	{
		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list';
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage = Db::GetRow("Select cp.*,u.login as login_manager, um.name as name_manager 
			from cart_package cp
			left join user u on u.id = cp.id_manager
			left join user_manager um on um.id_user = u.id 
			where cp.id=".Base::$aRequest['id']);
		if (!$aCartPackage || $aCartPackage['id_manager']==Auth::$aUser['id_user']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}

		Db::Execute("Update cart_package set id_manager=".Auth::$aUser['id_user']." where id=".$aCartPackage['id']);
		$sName=Auth::$aUser['login'];
		if (Auth::$aUser['name'])
			$sName = Auth::$aUser['name']; 

		Base::$oResponse->addAssign ( 'name_manager', 'innerHTML', $sName );
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseOrderItems(&$aItem) {
		if (!$aItem) {
			Base::$tpl->assign('sDisableSplitOrder',1);
			return;
		}
		$dOrderSubtotal = 0;
		$dOrderSubtotalProfit = 0;
		foreach ($aItem as $iKey => $aValue) {
			if (Base::$aRequest['status_order']=='return')
				$aItem[$iKey]['order_status_select'] = $this->BuildStatusItemReturn($aValue['order_status'],$aValue);
			
			$dPrice = doubleval(Currency::PrintPrice($aValue['price'],0,0,'<none>'));
			$aItem[$iKey]['price_total'] = round($aValue['number'] * $dPrice,2);
			$aItem[$iKey]['profit'] = $aItem[$iKey]['price_total'] - 
				round($aValue['number'] * $aValue['price_original_one_currency'],2);
			/*if (Base::$aRequest['status_order'])
				$aItem[$iKey]['class_tr'] = 'label-'.Base::$aRequest['status_order'];*/
			if ($aItem[$iKey]['order_status'] == 'return_customer' || 
				$aItem[$iKey]['order_status'] == 'return_provider' ||
				$aItem[$iKey]['order_status'] == 'return_provider_ok' ||
				$aItem[$iKey]['order_status'] == 'return_provider_no')
				$aItem[$iKey]['class_tr'] = 'label-return_provider_ok';
			else
				$aItem[$iKey]['class_tr'] = 'label-'.$aItem[$iKey]['order_status'];
			
			AManagerPanelManagerPackageList::AddTooltip($aItem[$iKey]);
			$dOrderSubtotalProfit += $aItem[$iKey]['profit'];
			$dOrderSubtotal += $aItem[$iKey]['price_total'];
		}
		Base::$tpl->assign('sOrderItemsCount',count($aItem));
		Base::$tpl->assign('iCartItems',count($aItem));
		Base::$tpl->assign('dOrderSubtotal',$dOrderSubtotal);
		Base::$tpl->assign('dOrderSubtotalProfit',$dOrderSubtotalProfit);
	}
	//-----------------------------------------------------------------------------------------------
	public function	AddTooltip(&$aData = array()) {
		static $aPurchaseDetailOrderStatus;

		if (!$aPurchaseDetailOrderStatus)
			$aPurchaseDetailOrderStatus = Base::$tpl->get_template_vars('aPurchaseDetailOrderStatus');
		
		$sTitle = Language::getMessage('status_'.$aData['order_status']);
		if (in_array($aData['order_status'],$aPurchaseDetailOrderStatus))
			$sTitle = Language::getMessage('status_ps_'.$aData['order_status']);

		if ($aData['post_date_incoming'])
			$sTitle .="<br><br>".Language::getMessage('incoming').":<br>".$aData['post_date_incoming'];
		
		$aData['tr_tooltip']=array(
			'data-toggle'=>'tooltip',
			'title'=>$sTitle,
			'data-placement'=>"left"
		);
		
		Base::$aRequest['tooltip'] = 1;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseOrderItemsRefused(&$aItem) {
		if (!$aItem)
			return;
		
		foreach ($aItem as $iKey => $aValue) {
			$aItem[$iKey]['class_tr'] = 'label-refused';
		}
		Base::$aRequest['item_refused']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetManagerComment()
	{
		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list';
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage = Db::GetRow("Select * from cart_package where id=".Base::$aRequest['id']);
		if (!$aCartPackage) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		
		Db::Execute("Update cart_package set manager_comment='".
			mysql_real_escape_string(Base::$aRequest['manager_comment']).
			"' where id=".$aCartPackage['id']);
		
		// log
		Base::$db->Execute("insert into cart_package_manager_comment_log (id_cart_package,id_user_manager,post_date,comment,ip)
	    	values ('".$aCartPackage['id']."','".Auth::$aUser['id_user']."','".date("Y-m-d H:i:s")."','".
			mysql_real_escape_string(Base::$aRequest['manager_comment'])."','".Auth::GetIp()."')");

		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list_view&id='.$aCartPackage['id'];
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewLogcp()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (Base::$aRequest['display']=='block') {
			Base::$oResponse->addScript ("$('.js_manager_panel_popup_log').hide();");
			return;
		}
		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list';
		
		$aLog = Db::getAll("Select l.*, unix_timestamp(now())-unix_timestamp(l.post_date) as mody from cart_package_log l
			left join user_manager um on um.id_user = l.id_user_manager
			left join user u on u.id = l.id_user_manager
			where order_status = 'assembled' || order_status='removed_cart' order by post_date desc limit 100");
		if ($aLog) {
			foreach ($aLog as $iKey => $aVal) {
				$aData = parse_timestamp($aVal['mody']);
				$sDiff = '';
				if ($aData['month'] && $aData['year']) {
					$sDiff = date("d/m/Y H:i",strtotime($aVal['post_date']));
				}
				elseif ($aData['month'] && !$aData['year']) {
					$sDiff = date("d/m H:i",strtotime($aVal['post_date']));
				}
				elseif ($aData['day']) {
					if ($aData['day']==1)
						$sDiff = 'Вчера, '.date("H:i",strtotime($aVal['post_date']));
					else
						$sDiff = date("d/m H:i",strtotime($aVal['post_date']));
				}
				else {
					if ($aData['hour'])
						$sDiff .= $aData['hour'].'ч ';
					if ($aData['min'])
						$sDiff .= $aData['min'].'м ';
				}
				$aLog[$iKey]['created'] = $sDiff;
				if ($aVal['order_status']!='removed_cart')
					$aLog[$iKey]['name_status'] = Language::getMessage('status_'.$aVal['order_status']);
			}
			Base::$tpl->assign('aLogCP',$aLog);
		}
	
		$sBody = Base::$tpl->fetch('manager_panel/manager_package_list/view_logcp.tpl');
	
		if (Auth::$aUser['is_warning'])
			Db::Execute("Update user_manager set is_warning=0 where id_user=".Auth::$aUser['id_user']);
		
		Base::$oResponse->addAssign ( 'body_popup_logcp', 'innerHTML', $sBody );
		//Base::$oResponse->addScript ("$('.block-popup').css({width:'750',overflow:'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup_log').show();");
		Base::$oResponse->addScript ("$(\"#id_ding\").attr(\"src\",\"/image/design/ding.png\")");
	}
	//-----------------------------------------------------------------------------------------------
	public function BuildStatusItemReturn($sItemStatus,$aData=array()) {
		static $aListStatus;
				
		if (!$aListStatus)
			$aListStatus = array('return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no');			
			
		if ($sItemStatus == 'return_customer' ||
			$sItemStatus == 'return_provider' ||
			$sItemStatus == 'return_provider_ok' ||
			$sItemStatus == 'return_provider_no')
			$sStyle = 'return_provider_ok';
		else
			$sStyle = $sItemStatus;
		
		$sOptions='';
		foreach ($aListStatus as $sStatus) {
			if ($sStatus!=$sItemStatus)
				$sOptions .= "<option value='".$sStatus."'>".Language::getMessage('status_'.$sStatus)."</option>";
		}
		return "<select class='selectpicker' data-style='btn-".$sStyle."' 
					onchange=\"xajax_process_browse_url('/?action=manager_panel_manager_package_list_change_status_item&amp;id=".$aData['id'].
						"&amp;return=".urlencode(Base::$aRequest['return']).						
						"&amp;status='+this.options[this.selectedIndex].value); return false;\">
					<option value='".$sItemStatus."' selected>".Language::getMessage('status_'.$sItemStatus)."</option>
					".$sOptions."
				</select>";
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeStatusItem() {
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect();
			return;
		}
	
		$aCart = Db::GetRow("Select * from cart where id=".Base::$aRequest['id']." and id_cart_package!=0");
		if ($aCart && $aCart['order_status']!=Base::$aRequest['status']) {
			$oManager = new Manager();
			$oManager->ProcessOrderStatus($aCart['id'],Base::$aRequest['status']);
		}
		parent::ManagerPanelRedirect();
	}
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