<?php 
/**
 * @author Vladimir Fedorov
 */

class AManagerPanelCreateOrder extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		$sTableTemplate = 'manager_panel/create_order/form_package_create.tpl';	
		$sDataTemplate = 'manager_panel/create_order/row_order_items.tpl';
		include_once SERVER_PATH.'/manager_panel/spec/manager_panel_manager_package_list.php';
		
		if ($_SESSION['create_order']['id_user']) {
			$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$_SESSION['create_order']['id_user'])));
			if ($aUser) {
				$aDeliveryAssoc = Cart::AssignDeliveryMethods(true);
				if ($aDeliveryAssoc[$_SESSION['create_order']['id_delivery']])
					$aUser['delivery_type_name'] = $aDeliveryAssoc[$_SESSION['create_order']['id_delivery']];
				$aUser['new_city'] = $_SESSION['create_order']['city'];
				$aUser['new_name'] = $_SESSION['create_order']['name'];
				$aUser['id_delivery'] = $_SESSION['create_order']['id_delivery'];
				$aUser['id_payment'] = $_SESSION['create_order']['id_payment'];
				
				$sBalance = Base::$tpl->fetch('manager_panel/template/balance.tpl');
				Base::$tpl->assign('sBalance',$sBalance);
				
				$aDeliveryTypeRow=Db::GetRow(Base::GetSql('DeliveryType',array(
					'id'=>$_SESSION['create_order']['id_delivery'],
					'visible'=>1,
				)));
				if ($aDeliveryTypeRow['price']) 
					Base::$tpl->assign('iDeliveryPrice',$aDeliveryTypeRow['price']);
				
				$aPaymentTypeRow=Db::GetRow(Base::GetSql('PaymentType',array(
					'id'=>$_SESSION['create_order']['id_payment'],
					'visible'=>1,
				)));
				if ($aPaymentTypeRow)
					$aUser['payment_name'] = $aPaymentTypeRow['name'];
				
				Base::$tpl->assign('aUser',$aUser);
			}
		}
			
		$oTable=new Table();
		$oTable->aDataFoTable=Db::getAll("Select c.*, cat.title as brand
			from cart c
			inner join cat on cat.pref = c.pref
			where c.type_= 'cart' and c.id_user = ".Auth::$aUser['id_user']." order by c.id desc");
		$oTable->sType = 'array';
		
		$oTable->aColumn=array(
				'brand'=>array('sTitle'=>'cat','nosort' => 1),
				'code'=>array('sTitle'=>'article','nosort' => 1),
				'name_translate'=>array('sTitle'=>'name','nosort' => 1),
				'number'=>array('sTitle'=>'cnt','nosort' => 1),
				'term'=>array('sTitle'=>'term','nosort' => 1),
				'price_original'=>array('sTitle'=>'price_original','nosort' => 1),
				'price'=>array('sTitle'=>'price','nosort' => 1),
				'price_total'=>array('sTitle'=>'total','nosort' => 1),
				'profit'=>array('sTitle'=>'s_profit','nosort' => 1),
				'provider_name'=>array('sTitle'=>'provider','nosort' => 1),
				//'action'=>array('nosort' => 1),
		);
		$oManagerPanelManagerPackageList = new AManagerPanelManagerPackageList();
		$oTable->iRowPerPage=10000;
		$oTable->aCallbackAfter=array($oManagerPanelManagerPackageList,'CallParseOrderItems');
		$oTable->sDataTemplate=$sDataTemplate;
		$oTable->bStepperVisible=false;
		$oTable->sClass="table table-striped";
		$oTable->sQueryString = Base::$sServerQueryString;
		
		Base::$tpl->assign('sOrderItems',$oTable->getTable());
		
		$sReturn = 'action='.Base::$aRequest['action'];
		Base::$tpl->assign('sReturn',urlencode($sReturn));
		
		Base::$sText .= Base::$tpl->fetch($sTableTemplate);

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function EditUser()
	{
		Cart::AssignDeliveryMethods();
		Cart::AssignPaymentType();
		
		if ($_SESSION['create_order']['id_user']) {
			$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
					'where' => " and uc.id_user='".$_SESSION['create_order']['id_user']."'"
			)));
			if($aCustomer) {
				$aCustomer['city'] = ($_SESSION['create_order']['city'] ? $_SESSION['create_order']['city'] : $aCustomer['city']);
				$aCustomer['name'] = ($_SESSION['create_order']['name'] ? $_SESSION['create_order']['name'] : $aCustomer['name']);
				Base::$tpl->assign('aData',$aCustomer);
				$iIdDeliveryType = ($_SESSION['create_order']['id_delivery'] ?  $_SESSION['create_order']['id_delivery'] : 0);
				Base::$tpl->assign('iIdDeliveryType',$iIdDeliveryType);
				$iIdPaymentType = ($_SESSION['create_order']['id_payment'] ?  $_SESSION['create_order']['id_payment'] : 0);
				Base::$tpl->assign('iIdPaymentType',$iIdPaymentType);
			}
		}
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('info_edit'));
		$sBody = Base::$tpl->fetch('manager_panel/create_order/form_edit_info.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'600','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		Base::$oResponse->addScript ("$('#user_phone_popup').mask(\"(099)999-99-99\",{placeholder:\"_\"});");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function FindUser()
	{
		if (!Base::$aRequest['phone'])
			return;
		
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
			'where' => " and uc.phone='".Base::$aRequest['phone']."'"
		)));
		if (!$aCustomer) {
			$this->ManagerPanelMessage ('MT_ERROR',Language::getMessage('not_found_user_by_phone'),'reg_error_popup');
			return;
		}
		Base::$oResponse->addScript ("$('#data_name').val('".$aCustomer['name']."');");
		Base::$oResponse->addScript ("$('#data_city').val('".$aCustomer['city']."');");
	}
	//-----------------------------------------------------------------------------------------------
	public function UserInfoApply() {
		if (!Base::$aRequest['phone'] || !Base::$aRequest['name'] 
			|| !Base::$aRequest['id_delivery'] || !Base::$aRequest['city'] 
			|| !Base::$aRequest['id_payment'])
			return;
		
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
				'where' => " and uc.phone='".Base::$aRequest['phone']."'"
		)));
		if (!$aCustomer) {
			$this->ManagerPanelMessage ('MT_ERROR',Language::getMessage('not_found_user_by_phone'),'reg_error_popup');
			return;
		}
		
		$_SESSION['create_order']['id_user'] = $aCustomer['id_user'];
		$_SESSION['create_order']['city'] = Base::$aRequest['city'];
		$_SESSION['create_order']['id_delivery'] = Base::$aRequest['id_delivery'];
		$_SESSION['create_order']['id_payment'] = Base::$aRequest['id_payment'];
		$_SESSION['create_order']['name'] = Base::$aRequest['name'];
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		Base::$aRequest['return'] = 'action=manager_panel_create_order';
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function Add()
	{
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_to_new_order') );
		$sBody = Base::$tpl->fetch('manager_panel/create_order/form_add_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'600','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	// search only code or zzz by price
	public function AddFind()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (!Base::$aRequest['code'])
			return;

		$sCodeOriginal = Base::$aRequest['code'];
		Base::$tpl->assign('sCode',$sCodeOriginal);
	
		$sCode=mb_strtoupper(Base::$aRequest['code']);
		$sCode=Catalog::StripCodeSearch($sCode);
		if(preg_match('/^A[0-9]{10}$/', $sCode) || preg_match('/^A[0-9]{11}$/', $sCode) || preg_match('/^A[0-9]{12}$/', $sCode))
			$sCode=ltrim($sCode,'A');
	
		if (strpos($sCode,"ZZZ_")!==false) {
			$bIsZzz=true;
			$iIdPrice = str_replace("ZZZ","",$sCode);
			$aCodeZzz=Db::GetRow("select * from price where id=".$iIdPrice);
			$sCode= $aCodeZzz['code'];
			$sPref = $aCodeZzz['pref'];
		}
		if(Base::GetConstant('complex_margin_enble','0')) {
			$sOrder=" pref_order, code_order";
			$sOrder.=",t.price, price_order , t.code, t.item_code  ";
		} else {
			$sOrder=" pref_order, code_order";
			Base::$aRequest ['order']="price";
			$sOrder.=" , p.price/cu.value asc , p.code, p.item_code  ";
		}
	
		$sSql=Base::GetSql('Catalog/Price',array(
				'aCode'=>array($sCode),
				'pref'=>$sPref,
				'customer_discount'=>Discount::CustomerDiscount($aUser),
				'pref_order'=>$sPref,
				'code_order'=>$sCode,
				'sId'=>$iIdPrice,
				'order'=>$sOrder
		));
	
		$aData=Db::GetAll($sSql);
		$oTable=new Table();
		$oTable->aDataFoTable=$aData;
		$oTable->sType = 'array';
		$oTable->aColumn=array(
				'brand'=>array('sTitle'=>'cat','nosort' => 1),
				'code'=>array('sTitle'=>'article','nosort' => 1),
				'name_translate'=>array('sTitle'=>'name','nosort' => 1),
				'image'=>array('nosort' => 1),
				'stock'=>array('sTitle'=>'cnt','nosort' => 1),
				'term'=>array('sTitle'=>'term','nosort' => 1),
				'price'=>array('sTitle'=>'price','nosort' => 1),
				'provider_name'=>array('sTitle'=>'provider','nosort' => 1),
				'action' => array('nosort' => 1),
		);
		$oTable->iRowPerPage=10000;
		$oTable->aCallbackAfter=array($this,'CallParse');
		$oTable->sDataTemplate='manager_panel/create_order/row_order_items_buy.tpl';
		$oTable->bStepperVisible=false;
		$oTable->sClass="table table-striped";
		$oTable->sQueryString = Base::$sServerQueryString;
	
		$sTableFind = $oTable->getTable('List found items');
		Base::$tpl->assign('sTableFind',$sTableFind);
	
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_to_new_order'));
		$sBody = Base::$tpl->fetch('manager_panel/create_order/form_add_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'auto','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem) {
		//select images
		$aCode=array();
		$aCodeTecdoc=array();
		if ($aItem) {
			foreach ($aItem as $sKey => $aValue) {
				if($aValue['code']) $aCode[]=$aValue['code'];
				if($aValue['code'] && !$aValue['hide_tof_image']) $aCodeTecdoc[]=$aValue['code'];
			}
		}
		$aCode=array_unique($aCode);
		$sCodes="'".implode("','", $aCode)."'";
	
		$aCodeTecdoc=array_unique($aCodeTecdoc);
		$sCodeTecdoc="'".implode("','", $aCodeTecdoc)."'";
	
		$aArtIds=TecdocDb::GetImages(array(
				'codes'=>$sCodes,
				'codesTD'=>$sCodeTecdoc,
		));
	
		if ($aItem) {
			foreach ($aItem as $sKey => $aValue) {
				$aItem[$sKey]['image']=$aArtIds[mb_strtoupper($aValue['item_code'],'utf-8')]['img_path'];
			}
		}
		//end images
	}
	//------------------------------------------------------------------------------------
	public function AddFindApply()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (!Base::$aRequest['id'] || Base::$aRequest['number']<=0)
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$_SESSION['create_order']['id_user'])));
		if (!$aUser)
			return;
		
		$a=Db::GetRow(Base::GetSql('Catalog/Price',array(
				'is_not_check_item_code' => 1,
				'not_change_recalc' => 1,
				'where'=>" and p.id='".Base::$aRequest['id']."' ",
				'customer_discount'=>Discount::CustomerDiscount($aUser)
		)));
		if (!$a || $a['price']==0)
			return;
	
		$iNumber = Base::$aRequest['number'];
		$a['zzz_code'] = $a['id'];
		$a['id_currency_user']=($aUser['id_currency']?$aUser['id_currency']:1);
		$a['price_currency_user'] = Currency::PrintPrice($a['price'],$aUser['id_currency'],2,"<none>")*$iNumber;
		unset($a['id']);
		unset($a['post_date']);
		$a['id_user']=Auth::$aUser['id'];	// $aUser['id']; - after save new order  
		$a['session']=session_id();
		$a['number']=Base::$aRequest['number'];
		$a['price_parent_margin']=$a['price_original']*$aUser['parent_margin']/100;
		$a['price_parent_margin_second']=$a['price_original']*$aUser['parent_margin_second']/100;
		$a['id_provider_ordered']=$a['id_provider'];
		$a['provider_name_ordered']=$a['provider_name'];
	
		$a['type_']='cart';
		Db::AutoExecute("cart", $a);
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		Base::$aRequest['return'] = 'action=manager_panel_create_order';
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function AddOptional()
	{
		//Base::$aRequest['not_change_top_menu']=1;

		Base::$tpl->assign('aListBrand',Db::getAssoc("Select pref,title from cat where visible=1 order by title"));
		Base::$tpl->assign('aListProvider',Db::GetAssoc("Assoc/UserProvider",array(" and u.visible=1")));
	
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_optional_new_order'));
		$sBody = Base::$tpl->fetch('manager_panel/create_order/form_add_optional_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'500','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
	}
	//-----------------------------------------------------------------------------------------------
	public function AddOptionalApply() {
	
		$aCat = Db::getRow("Select * from cat where visible=1 and pref='".Base::$aRequest['pref']."'");
		if (!$aCat)
			return;
	
		$aProvider = Db::getRow(Base::GetSql('UserProvider',array(
				'where' => " and u.visible=1 and u.id=".Base::$aRequest['id_provider'])));
		if (!$aProvider)
			return;
	
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$_SESSION['create_order']['id_user'])));
		if (!$aUser)
			return;
	
		if (!Base::$aRequest['code'] || !Base::$aRequest['pref'] || !Base::$aRequest['qty']
		|| !Base::$aRequest['name'] || !Base::$aRequest['price_original'] || !Base::$aRequest['id_provider'])
			$sError = Language::getMessage('please fill all required fields');
	
		if (Base::$aRequest['qty']<=0 || Base::$aRequest['price_original']<=0)
			$sError = Language::getMessage('incorrect fill number fields');
	
		if ($sError) {
			$this->ManagerPanelMessage ('MT_ERROR',$sError,'reg_error_popup');
			return;
		}
		$sCode=mb_strtoupper(Base::$aRequest['code']);
		$sCode=Catalog::StripCodeSearch($sCode);
		$sItemCode = $aCat['pref']."_".$sCode;
		$iNumber = intval(Base::$aRequest['qty']);
		$aPrice = Db::getRow("Select * from price where item_code='".$sItemCode
				."' and id_provider=".$aProvider['id_user']);
		if (!$aPrice) {
			$aDataPrice=array(
					'item_code' => $sItemCode,
					'id_provider' => $aProvider['id_user'],
					'code' => $sCode,
					'code_in' => mysql_real_escape_string(Base::$aRequest['code']),
					'price' => Base::$aRequest['price_original'],
					'part_rus' => mysql_real_escape_string(Base::$aRequest['name']),
					'pref' => $aCat['pref'],
					'cat' => $aCat['name'],
					'post_date' => date("Y-m-d H:i:s"),
			);
			Db::AutoExecute("price", $aDataPrice);
			$iIdPrice = Db::InsertId();
			$iSavePrice = Base::$aRequest['price_original'];
		}
		else {
			$iIdPrice = $aPrice['id'];
			$iSavePrice = $aPrice['price'];
		}
			
		$a=Db::GetRow(Base::GetSql('Catalog/Price',array(
				'is_not_check_item_code' => 1,
				'not_change_recalc' => 1,
				'where'=>" and p.id='".$iIdPrice."' ",
				'customer_discount'=>Discount::CustomerDiscount($aUser)
		)));
		if (!$a || $a['price']==0)
			return;
	
		Db::Execute("Update price set price='".$iSavePrice."' where id=".$iIdPrice);
	
		$a['zzz_code'] = $a['id'];
		$a['id_currency_user']=($aUser['id_currency']?$aUser['id_currency']:1);
		$a['price_currency_user'] = Currency::PrintPrice($a['price'],$aUser['id_currency'],2,"<none>")*$iNumber;
		unset($a['id']);
		unset($a['post_date']);
		$a['id_user']=Auth::$aUser['id']; // $aUser['id']; - after save new order
		$a['session']=session_id();
		$a['number']=$iNumber;
		$a['price_parent_margin']=$a['price_original']*$aUser['parent_margin']/100;
		$a['price_parent_margin_second']=$a['price_original']*$aUser['parent_margin_second']/100;
		$a['id_provider_ordered']=$a['id_provider'];
		$a['provider_name_ordered']=$a['provider_name'];
	
		$a['type_']='cart';
		Db::AutoExecute("cart", $a);
	
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		//Base::$aRequest['not_change_top_menu']=1;
		Base::$aRequest['return'] = 'action=manager_panel_create_order';
		parent::ManagerPanelRedirect();
		$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('item_added_to_order'),'table_error');
	}
	//-----------------------------------------------------------------------------------------------
	public function Create()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (!$_SESSION['create_order']['id_user'])
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$_SESSION['create_order']['id_user'])));
		if (!$aUser)
			return;
		
		$aDeliveryAssoc = Cart::AssignDeliveryMethods(true);
		if (!$aDeliveryAssoc[$_SESSION['create_order']['id_delivery']])
			return;
		
		$aPaymentAssoc = Cart::AssignPaymentType(true);
		if (!$aPaymentAssoc[$_SESSION['create_order']['id_payment']])
			return;
		
		$aUser['delivery_type_name'] = $aDeliveryAssoc[$_SESSION['create_order']['id_delivery']];
		$aUser['new_city'] = $_SESSION['create_order']['city'];
		$aUser['new_name'] = $_SESSION['create_order']['name'];
		$aUser['id_delivery'] = $_SESSION['create_order']['id_delivery'];
		$aUser['id_payment'] = $_SESSION['create_order']['id_payment'];
		
		$aDeliveryTypeRow=Db::GetRow(Base::GetSql('DeliveryType',array(
				'id'=>$_SESSION['create_order']['id_delivery'],
				'visible'=>1,
		)));
		if ($aDeliveryTypeRow['price'])
			$iDeliveryPrice = $aDeliveryTypeRow['price'];

		// copy from cart - PaymentEnd
		$sUserCartSql=Base::GetSql("Part/Search",array(
			"type_"=>'cart',
			"where"=> " and c.id_user='".Auth::$aUser['id']."'",
		));
		$aUserCart=Db::GetAll($sUserCartSql);
		if (!$aUserCart)
			return;

		$aUserCartId=array();
		foreach ($aUserCart as $iKey => $aValue) {
			$dPriceTotal+=Currency::PrintPrice($aValue['price'],1,2,"<none>")*$aValue['number'];
			// field price_currency_user already sum by number and round
			$dPriceTotalCurrencyUser+=$aValue['price_currency_user'];
		
			//$dPriceTotal+=$aValue['price']*$aValue['number'];
			$aUserCartId[]=$aValue['id'];
			$aUserCart[$iKey]['print_price'] = Currency::PrintPrice($aValue['price'],1,2,"<none>");
			$aUserCart[$iKey]['print_price_user'] = Currency::PrintPrice($aValue['price'],null,2,"<none>");
		}
		
		$sStatus = 'new'; // old pending AT-1277
		$aCartpackageInsert=array(
			'id_user'=>$aUser['id_user'],
			'price_total'=>$dPriceTotal + $iDeliveryPrice,
			'price_total_currency_user'=>$dPriceTotalCurrencyUser + $iDeliveryPrice,
			'id_currency_user' => $aUser['id_currency'],
			'order_status'=> $sStatus,
			'id_delivery_type'=>$aUser['id_delivery'],
			'id_payment_type'=>$aUser['id_payment'],
			'price_delivery'=>$iDeliveryPrice,
			'customer_comment'=>'',
			'is_need_check' => 0,
			'id_own_auto' => 0,
			'is_web_order' => (Auth::$aUser['type_']==manager ? 0 : 1),
			'post_date' => date("Y-m-d H:i:s"),
			'post_date_changed' => date("Y-m-d H:i:s"),
			'id_manager' => Auth::$aUser['id_user'],
		);
		$aCartpackageInsert=String::FilterRequestData($aCartpackageInsert);
		Db::AutoExecute('cart_package',$aCartpackageInsert);
		$iCartPackageId=Base::$db->Insert_ID();
		// log
		Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iCartPackageId."',0,'".date("Y-m-d H:i:s")."','".$sStatus."','','".Auth::GetIp()."')");
		
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>$iCartPackageId,)));
		
		Db::Execute("update cart set type_='order', id_cart_package='$iCartPackageId' ,order_status='pending', id_user='".$aUser['id_user']."'
		where id in (".implode(',',$aUserCartId).")");
		
		$aSmartyTemplate=String::GetSmartyTemplate('cart_package_details', array(
				'aCartPackage'=>$aCartPackage,
				'aCart'=>$aUserCart,
		));

		Mail::AddDelayed(($aUser['email'] ? $aUser['email'] : Auth::$aUser['email'])
		,$aSmartyTemplate['name'].$aCartPackage['id'],
		$aSmartyTemplate['parsed_text']);
		
		if (Base::GetConstant("manager:enable_order_notification_on_email","1")) {
			$aSmartyTemplate=String::GetSmartyTemplate('manager_mail_order', array(
					'aCartPackage'=>$aCartPackage,
					'aCart'=>$aUserCart,
			));
			Mail::AddDelayed(Auth::$aUser['manager_email'].", ".Base::GetConstant('manager:email_recievers','info@mstarproject.com')
			,$aSmartyTemplate['name']." ".$aCartPackage['id'],
			$aSmartyTemplate['parsed_text'],'',"info",false);
		}
		
		if ($aCartPackage['id'] && Finance::HaveMoney($aCartPackage['price_total'],$aCartPackage['id_user'])) {
			Cart::SendPendingWork($aCartPackage['id']);
		}
		
		unset($_SESSION['create_order']);
		
		Base::$aRequest['return'] = 'action=manager_panel_create_order';
		parent::ManagerPanelRedirect();
		$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('order_created'),'table_error');
	}
	//------------------------------------------------------------------------------------
	public function Clear()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		Db::Execute("Delete from cart where type_='cart' and id_user='".Auth::$aUser['id_user']."'");
		
		Base::$aRequest['return'] = 'action=manager_panel_create_order';
		parent::ManagerPanelRedirect();
		$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('cart_clear'),'table_error');
	}
}