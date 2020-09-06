<?php 
/**
 * @author Vladimir Fedorov
 */

class AManagerPanelPurchase extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
		require(SERVER_PATH.'/include/order_status_config.php');
		Base::$tpl->assign('aPurchaseDetailOrderStatus',array("" => "")+$aPurchaseDetailOrderStatus);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$aRequest['change_top_menu']=1;
		include_once SERVER_PATH.'/manager_panel/spec/manager_panel_manager_package_list.php';
		
		$aPurchaseDetailOrderStatus = Base::$tpl->get_template_vars('aPurchaseDetailOrderStatus');
		
		$this->PreIndex ();
		
		// from session
		if (!Base::$aRequest['is_filter'] && $_SESSION['purchase_filter']) {
			Base::$aRequest['is_filter']=1;
			if ($_SESSION['purchase_filter']['id_provider'])
				Base::$aRequest['search']['provider'] = $_SESSION['purchase_filter']['id_provider'];
			if ($_SESSION['purchase_filter']['article'])
				Base::$aRequest['search']['article'] = $_SESSION['purchase_filter']['article'];
			if ($_SESSION['purchase_filter']['status'])
				Base::$aRequest['search']['status'] = $_SESSION['purchase_filter']['status'];
		}
		// from post or session
		if (Base::$aRequest['is_filter']) {
			if (Base::$aRequest['clear']) {
				unset($_SESSION['purchase_filter']);
			}
			else {
				$sTextFilter = array();
				if (Base::$aRequest['search']['provider']) {
					$aProvider=Db::getRow(Base::GetSql('UserProvider',array(
						'where' => " and u.visible=1 and u.id=".Base::$aRequest['search']['provider'])));
					if ($aProvider) {
						$sWhere .= ' and c.id_provider='.Base::$aRequest['search']['provider'];
						$sTextFilter[]= Language::GetMessage('provider').': '.$aProvider['name'];
						$_SESSION['purchase_filter']['id_provider'] = $aProvider['id_user'];
					}
				}
				if (Base::$aRequest['search']['article']) {
					$sCode = Catalog::StripCodeSearch(Base::$aRequest['search']['article']);
					if ($sCode) {
						$sWhere .= " and c.code='".$sCode."'";
						$sTextFilter[] = Language::GetMessage('article').': '.$sCode;
						$_SESSION['purchase_filter']['article'] = $sCode;
					}
				}
				if (Base::$aRequest['search']['status']) {
					if (in_array(Base::$aRequest['search']['status'],$aPurchaseDetailOrderStatus)) {
						$sWhere .= " and c.order_status='".Base::$aRequest['search']['status']."'";
						$sTextFilter[] = Language::GetMessage('status').': '.Language::GetMessage('status_ps_'.Base::$aRequest['search']['status']);
						$_SESSION['purchase_filter']['status'] = Base::$aRequest['search']['status'];
					}
				}
			}
			if ($sTextFilter) {
				$sTextFilter = Language::getMessage('set_filter').": ".implode("; ",$sTextFilter);
				Base::$tpl->assign('sTextFilter',$sTextFilter);
			}
		}

		
		Base::$sText.=Base::$tpl->fetch('manager_panel/purchase/form_detail_search.tpl');
		$sWhere .= " and cp.order_status='work' and c.order_status in ('new','".implode("','",$aPurchaseDetailOrderStatus)."')";
		$oTable=new Table();
		$oTable->sSql = Base::GetSql("Part/Search",array(
			"type_"=>'order',
			"where"=>$sWhere,
		));
		$oTable->aOrdered=" order by c.id desc ";
		$oTable->iRowPerPage = 50;
		$oTable->aColumn=array(
				'created'=>array('sTitle'=>'status update','nosort' => 1),
				'id_cart_package'=>array('sTitle'=>'#CP','nosort' => 1),
				'cat_name'=>array('sTitle'=>'cat','nosort' => 1),
				'code'=>array('sTitle'=>'article','nosort' => 1),
				'number'=>array('sTitle'=>'cnt','nosort' => 1),
				'delivery_type_name'=>array('sTitle'=>'delivery type purchase','nosort' => 1),
				'order_status'=>array('sTitle'=>'order status','nosort' => 1),
				'incoming'=>array('sTitle'=>'incoming','nosort' => 1),
				'provider'=>array('sTitle'=>'provider','nosort' => 1),
				'term'=>array('sTitle'=>'days.','nosort' => 1),
				'price_original'=>array('sTitle'=>'purchase','nosort' => 1),
				'kurs_currency'=>array('sTitle'=>'rates','nosort' => 1),
				'profit'=>array('sTitle'=>'s_profit','nosort' => 1),
				'action'=>array('nosort' => 1),
		);
		$oTable->sDataTemplate='manager_panel/purchase/row_details.tpl';
		$oTable->aCallbackAfter=array($this,'CallParseAfter');
		//$oTable->sButtonTemplate='manager/button_order.tpl';
		//$oTable->bCheckVisible=true;
		//$oTable->sSubtotalTemplate='manager_panel/purchase/subtotal_order.tpl';
		$oTable->bStepperVisible=true;
		$oTable->sClass="table table-striped";
		$oTable->bAjaxStepper = true;
		$oTable->sQueryString = Base::$sServerQueryString;
		//$this->SetDefaultTable($oTable);
		
		// macro sort table
		//ManagerPanel::SortTableMP();
		
		Base::$sText.=$oTable->getTable();
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		$this->AfterIndex ();

	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseAfter(&$aItem) {
		if (!$aItem)
			return;

		$aPurchaseDetailOrderStatus = Base::$tpl->get_template_vars('aPurchaseDetailOrderStatus');
		
		foreach ($aItem as $iKey => $aValue) {
			if ($aPurchaseDetailOrderStatus && in_array($aValue['order_status'],$aPurchaseDetailOrderStatus)) {
				$sClass = $aValue['order_status'];
				$aItem[$iKey]['class_tr'] = 'label-'.$sClass;
			}

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
			
			$dPrice = doubleval(Currency::PrintPrice($aValue['price'],0,0,'<none>'));
			$aItem[$iKey]['price_total'] = round($aValue['number'] * $dPrice,2);
			$aItem[$iKey]['profit'] = $aItem[$iKey]['price_total'] -
				round($aValue['number'] * $aValue['price_original'],2);
			
			/*if (in_array($aValue['order_status'],$aPurchaseDetailOrderStatus))
				$aItem[$iKey]['order_status_select'] = $this->BuildStatusDetail($aValue['order_status'],$aValue);*/
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeStatus() {
		if (!Base::$aRequest['id'] || !Base::$aRequest['sel'])
			return;
		
		$aCart = Db::getRow("Select c.* from cart c
			inner join cart_package cp on cp.id = c.id_cart_package and cp.order_status='work' 
			where c.id=".Base::$aRequest['id']." and c.order_status!='".Base::$aRequest['sel']."'");
		if ($aCart) {
			$oManager = new Manager();
			$oManager->ProcessOrderStatus($aCart['id'],Base::$aRequest['sel']);
		}
		
		if (Auth::$aUser['is_warning'])
			Base::$oResponse->addScript ("$(\"#id_ding\").attr(\"src\",\"/image/design/ding_alarm.png\")");
		
		Base::$aRequest['return'] = 'action=manager_panel_purchase';
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function BuildStatusDetail($sOrderStatus,$aData=array()) {
		static $aPurchaseDetailOrderStatus, $aListStatus;
		
		if (!$aListStatus)
			$aListStatus = array('new', 'work', 'confirmed', 'road', 'store', 'end', 
			'refused', 'pending', 'reclamation', 'reorder', 'return_customer', 
			'return_provider', 'return_store', 'accrued', 'reserve', 'self_delivery', 
			'delivery', 'redeemed', 'removed', 'return_provider_ok', 'return_provider_no');
		
		if (!$aPurchaseDetailOrderStatus)
			$aPurchaseDetailOrderStatus = Base::$tpl->get_template_vars('aPurchaseDetailOrderStatus');
		
		$sStyle = $sOrderStatus;
		$sOptions='';
		foreach ($aListStatus as $sStatus) {
			$sName = 'status_'.$sStatus;
			if (in_array($sStatus,$aPurchaseDetailOrderStatus))
				$sName = 'status_ps_'.$sStatus;
			
			if ($sStatus!=$sOrderStatus)
				$sOptions .= "<option value='".$sStatus."'>".Language::getMessage($sName)."</option>";
		}
		return "<select class='selectpicker' data-style='btn-".$sStyle."'
					onchange=\"xajax_process_browse_url('/?action=manager_panel_purchase_change_status&amp;id=".$aData['id'].
							"&amp;return=".urlencode(Base::$aRequest['return']).
							"&amp;status='+this.options[this.selectedIndex].value); return false;\">
					<option value='".$sOrderStatus."' selected>".Language::getMessage('status_'.$sOrderStatus)."</option>
					".$sOptions."
				</select>";
	}
	//-----------------------------------------------------------------------------------------------
	public function SaveCalendar() {
		if (!Base::$aRequest['id'] || !Base::$aRequest['value'])
			return;

		$aCart = Db::getRow("Select c.* from cart c where c.id=".Base::$aRequest['id']);
		if (!$aCart)
			return;

		Db::Execute("Update cart set post_date_incoming='".Base::$aRequest['value']."' where id=".$aCart['id']);
		
		Base::$oResponse->addScript ("alert('".Language::getMessage('date_incoming_saved')."');");
	}
	//-----------------------------------------------------------------------------------------------
	public function SaveValue() {
		if (!Base::$aRequest['id'] || !Base::$aRequest['value'] || !Base::$aRequest['type'])
			return;
	
		$aCart = Db::getRow("Select c.* from cart c where c.id=".Base::$aRequest['id']);
		if (!$aCart)
			return;
	
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$aCart['id_user'])));
		if (!$aUser)
			return;
		
		if (Base::$aRequest['type']=='kurs') {
			$dKurs = floatval(Base::$aRequest['value']);
			if ($dKurs<=0)
				return;
			
			$sNextValue=$dKurs;
			$sPreviousValue=$aCart['kurs_currency'];
			if ($dKurs==$sPreviousValue)
				return;
			
			$a=Base::GetSql('Catalog/Price',array(
				'is_not_check_item_code' => 1,
				'not_change_recalc' => 1,
				'where'=>" and p.id=".$aCart['zzz_code'],
				'customer_discount'=>Discount::CustomerDiscount($aUser)
			));
			$a = str_replace('cu.value',$dKurs,$a);
			$a = str_replace('mp.price','m_.price',$a);
			$a = str_replace('p.price',$aCart['price_original'],$a);
			$a = str_replace('m_.price','mp.price',$a);
			$aPrice = Db::getRow($a);
			if (!$aPrice['price'])
				return;
			
			$dProfit = round($aCart['number'] * doubleval(Currency::PrintPrice($aPrice['price'],0,0,'<none>')),2) -
				round($aCart['number'] * $aPrice['price_original'],2);
			
			$dAmount=$aCart['number']*($aCart['price']-$aPrice['price']);
			
			DB::Execute("update cart set price='".$aPrice['price']."', kurs_currency = '".$sNextValue."' where id='".$aCart['id']."'");
			DB::Execute("update cart set kurs_currency_before_change=".$sPreviousValue." where kurs_currency_before_change=0 and id='".$aCart['id']."'");
			$sComment = Language::getMessage('change_kurs').' '.$aCart['id']." : $sPreviousValue => $sNextValue";
			// check exist work status order
			$iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aCart['id_cart_package']." and operation='pending_work'");
			if ($iWorkPayAlready)
				Finance::Deposit($aCart['id_user'],$dAmount,$sComment,$aCart['id_cart_package']
					,'internal','cart',0,6,0,'',0,0,true,0,'','',0,$aCart['id']);
			
			Manager::SetPriceTotalCartPackage($aCart);

			// log for manager ?
			Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager,is_customer_visible)
				values ('".$aCart['id']."',UNIX_TIMESTAMP(),'change_kurs'
				,'$sComment',".Auth::$aUser["id"].",0)");
			
			Base::$oResponse->addScript("$('#id_profit_".$aCart['id']."').html('".$dProfit."')");
			Base::$oResponse->addScript("$('#id_kurs_".$aCart['id']."').html('".$dKurs."')");
			Base::$oResponse->addScript("$('#id_kurs_".$aCart['id']."').show();");
			Base::$oResponse->addScript("$('#id_kurs_edit_".$aCart['id']."').hide();");
			Base::$oResponse->addScript ("alert('".Language::getMessage('value_update')."');");
		}
		elseif (Base::$aRequest['type']=='price_original') {
			$dPriceO = floatval(Base::$aRequest['value']);
			if ($dPriceO<=0)
				return;
			
			$sNextValue=$dPriceO;
			$sPreviousValue=$aCart['price_original'];
			if ($dPriceO==$sPreviousValue)
				return;
			
			$a=Base::GetSql('Catalog/Price',array(
				'is_not_check_item_code' => 1,
				'not_change_recalc' => 1,
				'where'=>" and p.id=".$aCart['zzz_code'],
				'customer_discount'=>Discount::CustomerDiscount($aUser)
			));
			$a = str_replace('mp.price','m_.price',$a);
			$a = str_replace('p.price',$dPriceO,$a);
			$a = str_replace('m_.price','mp.price',$a);
			$aPrice = Db::getRow($a);
			if (!$aPrice['price'])
				return;
			
			$dProfit = round($aCart['number'] * doubleval(Currency::PrintPrice($aPrice['price'],0,0,'<none>')),2) -
				round($aCart['number'] * $aPrice['price_original'],2);
			
			$dAmount=$aCart['number']*($aCart['price']-$aPrice['price']);
			
			DB::Execute("update cart set price='".$aPrice['price']."', price_original = '".$sNextValue."' where id='".$aCart['id']."'");
			DB::Execute("update cart set price_original_before_change=".$sPreviousValue." where price_original_before_change=0 and id='".$aCart['id']."'");
			$sComment = Language::getMessage('change_price_original').' '.$aCart['id']." : $sPreviousValue => $sNextValue";
			// check exist work status order
			$iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aCart['id_cart_package']." and operation='pending_work'");
			if ($iWorkPayAlready)
				Finance::Deposit($aCart['id_user'],$dAmount,$sComment,$aCart['id_cart_package']
					,'internal','cart',0,6,0,'',0,0,true,0,'','',0,$aCart['id']);
			
			Manager::SetPriceTotalCartPackage($aCart);

			// log for manager
			Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager,is_customer_visible)
				values ('".$aCart['id']."',UNIX_TIMESTAMP(),'change_price_original'
				,'$sComment',".Auth::$aUser["id"].",0)");
			
			Base::$oResponse->addScript("$('#id_profit_".$aCart['id']."').html('".$dProfit."')");
			Base::$oResponse->addScript("$('#id_price_original_".$aCart['id']."').html('".$sNextValue."')");
			Base::$oResponse->addScript("$('#id_price_original_".$aCart['id']."').show();");
			Base::$oResponse->addScript("$('#id_price_original_edit_".$aCart['id']."').hide();");
			Base::$oResponse->addScript ("alert('".Language::getMessage('value_update')."');");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Filter() {
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('filter') );

		if ($_SESSION['purchase_filter']['id_provider'])
			Base::$tpl->assign('search_provider',$_SESSION['purchase_filter']['id_provider']);
		if ($_SESSION['purchase_filter']['article'])
			Base::$tpl->assign('search_article',$_SESSION['purchase_filter']['article']);
		if ($_SESSION['purchase_filter']['status'])
			Base::$tpl->assign('search_status',$_SESSION['purchase_filter']['status']);
		
		$aProvider = Db::getAssoc(Base::GetSql('Assoc/UserProvider',array(
			'where' => " and u.visible=1")));
		Base::$tpl->assign('aProvider',$aProvider);
		
		$sBody = Base::$tpl->fetch('manager_panel/purchase/form_filter_popup.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'500','owerflow':'hidden'});");
		//Base::$oResponse->addScript ("$('#user_phone').mask(\"(099)999-99-99\",{placeholder:\"_\"});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').slideToggle();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
}