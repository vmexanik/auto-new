<?php 
/**
 * @author Vladimir Fedorov
 */

class AManagerPanelEditOrder extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		include_once SERVER_PATH.'/manager_panel/spec/manager_panel_manager_package_list.php';
		$sReturn = 'action=manager_panel_manager_package_list_edit&id='.Base::$aRequest['id'];
		$sDataTemplate = 'manager_panel/edit_order/row_order_items_edit.tpl'; 
		$sTableTemplate = 'manager_panel/edit_order/form_package_edit.tpl';
		AManagerPanelManagerPackageList::View($sReturn,'edit',$sDataTemplate,$sTableTemplate);
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply()
	{
		if (!Base::$aRequest['id']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}

		Base::$aRequest['return'] = 'action=manager_panel_manager_package_list_view&id='.$aCartPackage['id'];
		if (!Base::$aRequest['name'] || !Base::$aRequest['price'] || !Base::$aRequest['number']) {
			parent::ManagerPanelRedirect();
			return;
		}

		$aCartItems = Db::getAssoc("Select id, c.* from cart c where id_cart_package=".$aCartPackage['id']);
		$aItems=explode("::",Base::$aRequest['price']);
		$sError = '';
		if ($aItems) {
			foreach ($aItems as $sVal) {
				if ($sVal=='')
					continue;
				list($id,$price) = explode("|",$sVal);
				if (is_numeric($id))
					$aPrice[$id] = $price;
				if ($price<=0) {
					$sError = Language::getMessage('field price is incorrect');
					break;
				}
				elseif (!$aCartItems[$id]) {
					parent::ManagerPanelRedirect();
					return;
				} 
			}
		}
		if (!$sError) {
			$aItems=explode("::",Base::$aRequest['number']);
			if ($aItems) {
				foreach ($aItems as $sVal) {
					if ($sVal=='')
						continue;
					list($id,$number) = explode("|",$sVal);
					if (is_numeric($id))
						$aNumber[$id] = $number;
					if ($number<=0) {
						$sError = Language::getMessage('field number is incorrect');
						break;
					}
					elseif (!$aCartItems[$id]) {
						parent::ManagerPanelRedirect();
						return;
					}
				}
			}
		}
		if (!$sError) {
			$aItems=explode("::",Base::$aRequest['name']);
			if ($aItems) {
				foreach ($aItems as $sVal) {
					if ($sVal=='')
						continue;
					list($id,$name) = explode("|",$sVal);
					if (is_numeric($id))
						$aName[$id] = $name;
					if ($name=='') {
						$sError = Language::getMessage('field name is incorrect');
						break;
					}
					elseif (!$aCartItems[$id]) {
						parent::ManagerPanelRedirect();
						return;
					}
				}
			}
		}
		if ($sError) {
			$this->ManagerPanelMessage ('MT_ERROR',$sError,'table_error');
			return;
		}
		// update
		$isEdit = 0;
		Base::$aRequest['ignore_confirm_growth']=1;
		$oManager = new Manager();
		foreach ($aCartItems as $iId => $aValue) {
			if ($aValue['name_translate']!=$aName[$iId]) {
				Db::Execute("Update cart set name_translate='".$aName[$iId].
					"' where id=".$iId);
				$isEdit=1;
			}
			if ($aValue['number']!=$aNumber[$iId]) { 
				$oManager->ProcessOrderStatus($iId,'change_quantity','','','','',$aNumber[$iId]);
				$isEdit=1;
			}
			if ($aValue['price']!=$aPrice[$iId]) {
				$oManager->ProcessOrderStatus($iId,'change_price','','','','',$aPrice[$iId]);
				$isEdit=1;
			}
		}
		if ($isEdit)
			Db::Execute("Update cart_package set post_date_changed='".date("Y-m-d H:i:s").
				"' where id = ".$aCartPackage['id']);
		
		Base::$aRequest['return'] = 'action=manager_panel_edit_order&id='.$aCartPackage['id'];
		parent::ManagerPanelRedirect();		
	}
	//-----------------------------------------------------------------------------------------------
	public function SetStatusRefuseItem()
	{
		if (!Base::$aRequest['id'] || !Base::$aRequest['c_id']) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage) {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		$aCart = Db::getRow("Select * from cart where id_cart_package=".$aCartPackage['id']." and id=".Base::$aRequest['c_id']);
		if (!$aCart || $aCart['order_status']=='refused') {
			parent::ManagerPanelRedirect(Base::$aRequest['return']);
			return;
		}
		
		$oManager = new Manager();
		$oManager->ProcessOrderStatus(Base::$aRequest['c_id'],'refused','','','','',$aPrice[$iId]);
		
		Db::Execute("Update cart_package set post_date_changed='".date("Y-m-d H:i:s").
		"' where id = ".$aCartPackage['id']);
		
		Base::$aRequest['return'] = 'action=manager_panel_edit_order&id='.$aCartPackage['id'];
		parent::ManagerPanelRedirect();		
	}
	//-----------------------------------------------------------------------------------------------
	public function Split()
	{
		include_once SERVER_PATH.'/manager_panel/spec/manager_panel_manager_package_list.php';
		$sReturn = 'action=manager_panel_manager_edit_order_split&id='.Base::$aRequest['id'];
		$sDataTemplate = 'manager_panel/edit_order/row_order_items_split.tpl'; 
		$sTableTemplate = 'manager_panel/edit_order/form_package_split.tpl';
		AManagerPanelManagerPackageList::View($sReturn,'split',$sDataTemplate,$sTableTemplate);
	}
	//-----------------------------------------------------------------------------------------------
	public function SplitForm()
	{
		if (!Base::$aRequest['id'] || !Base::$aRequest['checked'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;

		$aChecked = explode(',',Base::$aRequest['checked']);
		$aCartAssoc = Db::getAssoc("Select id as key_,id from cart where id_cart_package=".$aCartPackage['id']);
		if (!$aCartAssoc)
			return;
		
		foreach ($aChecked as $iId)
			if (!$aCartAssoc[$iId])
				return;		
		
		$aCartPackageList = Db::getAssoc("Select id, concat('#',id,' от ',date_format(post_date,'%d-%m-%Y')) as val 
			from cart_package where id_user=".$aCartPackage['id_user']." and id!=".$aCartPackage['id']." order by id desc");
		if ($aCartPackageList)
			Base::$tpl->assign('aCartPackageList',$aCartPackageList);
		
		Base::$tpl->assign('items_checked',Base::$aRequest['checked']);
		Base::$tpl->assign('id_cart_package',$aCartPackage['id']);
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('split_order').' #'.$aCartPackage['id'] );
		$sBody = Base::$tpl->fetch('manager_panel/edit_order/form_split_popup.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'300','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function SplitFormApply()
	{
		if (!Base::$aRequest['type'])
			return;
		
		if (!Base::$aRequest['id'] || !Base::$aRequest['items']) {
			Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
			//Base::$aRequest['not_change_top_menu']=1;
			return;
		}
		
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;
		
		$aCartPackageOld = $aCartPackage;
		$aChecked = explode(',',Base::$aRequest['items']);
		$aCartAssoc = Db::getAssoc("Select id as key_, cart.* from cart where id_cart_package=".$aCartPackage['id']);
		if (!$aCartAssoc)
			return;
		
		foreach ($aChecked as $iId) {
			if (!$aCartAssoc[$iId])
				return;
		}

		$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
		$dPriceTotal = 0;$dPriceTotalCurrencyUser=0;
		$iUserId = $aCartPackage['id_user'];
		foreach ($aChecked as $iId) {
			$aValue=$aCartAssoc[$iId];
			$dPriceTotal += Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>")*$aValue['number'];
			// field price_currency_user already sum by number and round
			$dPriceTotalCurrencyUser+=$aValue['price_currency_user'];
			$sLogStatus = 'create_order';
			if (Base::$aRequest['type']!='new')
				$sLogStatus = 'change_order';
			
			DB::Execute("insert into cart_log (id_cart, post, order_status, comment, id_user_manager)
				values (".$iId.", UNIX_TIMESTAMP(), '".$sLogStatus."', '".Language::getMessage('manager_split_from_order').': '.$aCartPackage['id']."', ".Auth::$aUser['id'].")");				
		}		

		// create new order - copy from cart::paymentEnd
		if (Base::$aRequest['type']=='new') {
			$sStatus = 'new'; // old pending AT-1277
			$aCartpackageInsert=array(
				'id_user'=>$iUserId,
				'price_total'=>$dPriceTotal + $aCartPackage['price_delivery'],
				'price_total_currency_user'=>$dPriceTotalCurrencyUser + $aCartPackage['price_delivery'],
				'id_currency_user' => $aCartPackage['id_currency_user'],
				'order_status'=> $sStatus,
				'id_delivery_type'=>$aCartPackage['id_delivery_type'],
				'id_payment_type'=>$aCartPackage['id_payment_type'],
				'price_delivery'=>$aCartPackage['price_delivery'],
				'customer_comment'=>$aCartPackage['customer_comment'],
				'is_need_check' => $aCartPackage['is_need_check'],
				'id_own_auto' => $aCartPackage['own_auto_id'],
				'is_web_order' => 0,
				'post_date' => date("Y-m-d H:i:s"),
				'post_date_changed' => date("Y-m-d H:i:s"),
				'id_manager' => $aCartPackage['id_manager']
			);
			$aCartpackageInsert=String::FilterRequestData($aCartpackageInsert);
			Db::AutoExecute('cart_package',$aCartpackageInsert);
			$iCartPackageId=Base::$db->Insert_ID();
			// log
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iCartPackageId."',0,'".date("Y-m-d H:i:s")."','".$sStatus."','','".Auth::GetIp()."')");
			
			$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>$iCartPackageId,)));
			
			Db::Execute("update cart set id_cart_package='$iCartPackageId' ,order_status='pending'
				where id in (".implode(',',$aChecked).")");
			
			if ($aCartPackage['id'] && Finance::HaveMoney($aCartPackage['price_total'],$aCartPackage['id_user'])) {
				Cart::SendPendingWork($aCartPackage['id']);
			}
		}
		else {
			$aCartPackageExist=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_exist'])));
			if (!$aCartPackageExist || $aCartPackage['id_user']!=$aCartPackageExist['id_user'])
				return;

			$iCartPackageId = $aCartPackageExist['id'];
			Db::Execute("update cart set id_cart_package='$iCartPackageId' 
				where id in (".implode(',',$aChecked).")");
				
			Db::Execute("update cart_package set post_date_changed='".date("Y-m-d H:i:s")."' where id='".$aCartPackageExist."'");
			Db::Execute("update cart_package set post_date_changed='".date("Y-m-d H:i:s")."' where id='".$aCartPackageOld."'");
			// log
			Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iCartPackageId."',0,'".date("Y-m-d H:i:s")."','".$sLogStatus."','','".Auth::GetIp()."')");
				
			//recalc exist order
			Manager::SetPriceTotalCartPackage($aCartPackageExist);
		}
		//recalc order
		Manager::SetPriceTotalCartPackage($aCartPackageOld);
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		//Base::$aRequest['not_change_top_menu']=1;
		Base::$aRequest['return'] = 'action=manager_panel_edit_order_split&id='.$aCartPackageOld['id'];
		parent::ManagerPanelRedirect();
	}
	//-----------------------------------------------------------------------------------------------
	public function Add()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (!Base::$aRequest['id'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;

		Base::$tpl->assign('id_cart_package',$aCartPackage['id']);
	
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_to_order').' #'.$aCartPackage['id'] );
		$sBody = Base::$tpl->fetch('manager_panel/edit_order/form_add_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'600','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
	}
	//-----------------------------------------------------------------------------------------------
	public function AddOptional()
	{
		//Base::$aRequest['not_change_top_menu']=1;
		if (!Base::$aRequest['id'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;
		
		Base::$tpl->assign('id_cart_package',$aCartPackage['id']);
		Base::$tpl->assign('aListBrand',Db::getAssoc("Select pref,title from cat where visible=1 order by title"));
		Base::$tpl->assign('aListProvider',Db::GetAssoc("Assoc/UserProvider",array(" and u.visible=1")));
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_optional_order').' #'.$aCartPackage['id'] );
		$sBody = Base::$tpl->fetch('manager_panel/edit_order/form_add_optional_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'500','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
	}
	//-----------------------------------------------------------------------------------------------
	public function AddOptionalApply() {

		if (!Base::$aRequest['id'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;
		
		$aCat = Db::getRow("Select * from cat where visible=1 and pref='".Base::$aRequest['pref']."'");
		if (!$aCat)
			return;

		$aProvider = Db::getRow(Base::GetSql('UserProvider',array(
			'where' => " and u.visible=1 and u.id=".Base::$aRequest['id_provider'])));
		if (!$aProvider)
			return; 
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$aCartPackage['id_user'])));
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
		$a['id_user']=$aUser['id'];
		$a['session']=session_id();
		$a['number']=$iNumber;
		$a['price_parent_margin']=$a['price_original']*$aUser['parent_margin']/100;
		$a['price_parent_margin_second']=$a['price_original']*$aUser['parent_margin_second']/100;
		$a['id_provider_ordered']=$a['id_provider'];
		$a['provider_name_ordered']=$a['provider_name'];
		
		$a['type_']='order';
		$a['id_cart_package']=$aCartPackage['id'];
		Db::AutoExecute("cart", $a);
		
		//recalc order
		Manager::SetPriceTotalCartPackage($a);
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		//Base::$aRequest['not_change_top_menu']=1;
		Base::$aRequest['return'] = 'action=manager_panel_edit_order&id='.$aCartPackage['id'];
		parent::ManagerPanelRedirect();
		$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('item_added_to_order'),'table_error');
	}
	//-----------------------------------------------------------------------------------------------
	// search only code or zzz by price
	public function AddFind()
	{
		if (!Base::$aRequest['code'])
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id'])));
		if (!$aCartPackage)
			return;
		
		Base::$tpl->assign('id_cart_package',$aCartPackage['id']);
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
		$oTable->sDataTemplate='manager_panel/edit_order/row_order_items_buy.tpl';
		$oTable->bStepperVisible=false;
		$oTable->sClass="table table-striped";
		$oTable->sQueryString = Base::$sServerQueryString;
		
		$sTableFind = $oTable->getTable('List found items');
		Base::$tpl->assign('sTableFind',$sTableFind);
	
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('add_to_order').' #'.$aCartPackage['id'] );
		$sBody = Base::$tpl->fetch('manager_panel/edit_order/form_add_popup.tpl');
	
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'auto','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
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
		if (!Base::$aRequest['id'] || Base::$aRequest['number']<=0)
			return;
			
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cp'])));
		if (!$aCartPackage)
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>$aCartPackage['id_user'])));
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
		
		$a['zzz_code'] = $a['id'];
		$a['id_currency_user']=($aUser['id_currency']?$aUser['id_currency']:1);
		$a['price_currency_user'] = Currency::PrintPrice($a['price'],$aUser['id_currency'],2,"<none>")*$iNumber;		
		unset($a['id']);
		unset($a['post_date']);
		$a['id_user']=$aUser['id'];
		$a['session']=session_id();
		$a['number']=Base::$aRequest['number'];
		$a['price_parent_margin']=$a['price_original']*$aUser['parent_margin']/100;
		$a['price_parent_margin_second']=$a['price_original']*$aUser['parent_margin_second']/100;
		$a['id_provider_ordered']=$a['id_provider'];
		$a['provider_name_ordered']=$a['provider_name'];
		
		$a['type_']='order';
		$a['id_cart_package']=$aCartPackage['id'];
		Db::AutoExecute("cart", $a);
		
		//recalc order
		Manager::SetPriceTotalCartPackage($a);
		
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').hide();");
		//Base::$aRequest['not_change_top_menu']=1;
		Base::$aRequest['return'] = 'action=manager_panel_edit_order&id='.$aCartPackage['id'];
		parent::ManagerPanelRedirect();		
	}
}