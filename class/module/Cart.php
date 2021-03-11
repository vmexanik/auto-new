<?

/**
 * @author Mikhail Starovoyt
 * @author Roman Dehtyarov
 * @version 4.5.3
 */

class Cart extends Base
{
	public $sExportSql;

	//-----------------------------------------------------------------------------------------------
	public function __construct($bNeedAuth=true)
	{
		Repository::InitDatabase('cart');

		if (Base::$aRequest['action']=='cart_add_cart_item' &&  !Auth::IsAuth()
		&& Base::$aRequest['xajax']) {
			$aRegisteredUser=Auth::AutoCreateUser();
			Auth::Login($aRegisteredUser['login'],$aRegisteredUser['password_temp'],false,true,
			Base::GetConstant('user:is_salt_password',1));
		}

		if (Auth::$aUser['type_']!='manager' && $bNeedAuth && !Base::$aRequest['xajax']) 
		    Auth::NeedAuth('customer');
		Base::$aData['template']['bWidthLimit']=true;
		Base::$bXajaxPresent=true;

		$oContent = new Content();//For template assign hack
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->CartList();
	}
	//-----------------------------------------------------------------------------------------------
	public function CartList()
	{
		Base::$oContent->AddCrumb(Language::GetMessage('cart items'),'');
		Base::$aTopPageTemplate=array('panel/tab_customer_cart.tpl'=>'cart');
		Base::$tpl->assign_by_ref("oCatalog", new Catalog());
		Base::Message();
		
		if (Base::$aRequest['is_post']) {
			if (Base::$aRequest['number']<=0) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='cart_cart_edit';
				Base::$tpl->assign('aData',Base::$aRequest);
			}
			else {
				//[----- UPDATE -----------------------------------------------------]
				$sQuery="update cart set
								number='".Base::$aRequest['number']."',
								customer_comment='".Base::$aRequest['customer_comment']."',
								customer_id='".Base::$aRequest['customer_id']."'
		                        		where id='".Base::$aRequest['id']."'  and type_='cart' ".Auth::$sWhere;
				//[----- END UPDATE -------------------------------------------------]
				Base::$db->Execute($sQuery);
				//Form::AfterReturn('cart_cart');
				Base::Redirect('/pages/cart_cart/');
			}
		}

		if (Base::$aRequest['action']=='cart_cart_edit') {
			Form::BeforeReturn('cart_cart','cart_cart_edit');

			if (Base::$aRequest['action']=='cart_cart_edit') {
				$aUserCart=Db::GetRow("select * from cart where id='".Base::$aRequest['id']."'
							and type_='cart' ".Auth::$sWhere);
				Base::$tpl->assign('aData',$aUserCart);
			}

			$aField['code']=array('title'=>'Code','type'=>'text','value'=>$aUserCart['code_visible']?$aUserCart['code']:Language::GetMessage('cart_invisible'));
			$aField['name']=array('title'=>'Name','type'=>'text','value'=>$aUserCart['name']);
			$aField['number']=array('title'=>'Number','type'=>'input','value'=>$aUserCart['number'],'name'=>'number');
			$aField['customer_comment']=array('title'=>'Customer comment','type'=>'textarea','name'=>'customer_comment','value'=>$aUserCart['customer_comment']);
			$aField['customer_id']=array('title'=>'Custoemr Database ID','type'=>'input','value'=>$aUserCart['customer_id'],'name'=>'customer_id');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cart item",
			//'sContent'=>Base::$tpl->fetch('cart/form_cart.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'cart_cart',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'cart_cart',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();
			return;
		}
		
		// get list expired history positions
		$aExpiredCount = $this->CartExpiredCountPositions();
		if ($aExpiredCount > 0) {
			Base::$tpl->assign('sTableMessage',Language::GetText("exist_expired_cart"));
			Base::$tpl->assign('sTableMessageClass','warning_p');
		}
		
		if (Base::$aRequest['action']=='cart_cart_delete') {
			Form::BeforeReturn('cart_cart','cart_cart_delete');
			if (Base::$aRequest['row_check']) {
				Base::$db->Execute("delete from cart where id in (".implode(',',Base::$aRequest['row_check']).")
					and type_='cart'
					".Auth::$sWhere);
			}
			else 
				Base::$db->Execute("delete from cart where id='".Base::$aRequest['id']."'
					and type_='cart'
					".Auth::$sWhere);
			
			if(!Base::$aRequest['xajax']) {
			    Base::Redirect ( '/pages/cart_cart/' );
			} else {
			    Cart::ShowPopupCart();
			}
		}
		
		if (Base::$aRequest ['action'] == 'cart_cart_clear') {
			Base::$db->Execute ( "delete from cart where 1=1
					and type_='cart'
					" . Auth::$sWhere );
			
			if(!Base::$aRequest['xajax']) {
			    Base::Redirect ( '/pages/cart_cart/' );
			} else {
			    Cart::ShowPopupCart();
			}
		}

		$sWhere.=" and c.id_user=".Auth::$aUser['id'];
		$aDataCart=Db::GetAll($sSqlCart=Base::GetSql("Part/Search",array(
		"type_"=>'cart',
		"where"=>$sWhere,
		)));

		$aSubtotalCart=Cart::CallParseCart($aDataCart);
		$_SESSION['cart']['table_sql']=$sSqlCart;
		
		if (empty($aDataCart)){
		   Base::$tpl->assign('sTableMessage',Language::GetMessage("cart empty"));
		}
		Base::$tpl->assign('aSubtotalCart',$aSubtotalCart);
		Base::$tpl->Assign('aDataCart',$aDataCart);
		Base::$sText=Base::$tpl->fetch('cart/index.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignDeliveryMethods($isAssoc=false)
	{
		$aData=array(
				'table'=>'delivery_type',
				'where'=>" and t.visible=1 order by t.num ",
		);
		if ($isAssoc) {
			$aDeliveryType=Language::GetLocalizedAll($aData,false,'id,name,');
			$aAssoc = array();
			foreach ($aDeliveryType as $iKey => $aValue) {
				$aAssoc[$iKey] = $aValue['name'];
			}
			$aDeliveryType = $aAssoc;
		}
		else
			$aDeliveryType=Language::GetLocalizedAll($aData);
		Base::$tpl->assign('aDeliveryType',$aDeliveryType);
		//Base::$tpl->assign('aDeliveryType',Db::GetAll(Base::GetSql('DeliveryType',array('visible'=>1))));
		/*$aDeliveryTypeRow=Db::GetRow(Base::GetSql('DeliveryType',array(
		'id'=>$_SESSION['current_cart']['id_delivery_type'],
		'visible'=>1,
		)));
		Base::$tpl->assign('aDeliveryTypeRow',$aDeliveryTypeRow);*/
		return $aDeliveryType;
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignPaymentType($isAssoc = false)
	{
		$aData=array(
				'table'=>'payment_type',
				'where'=>" and t.visible=1 order by t.num",
		);
		if ($isAssoc) {
			$aPaymentType=Language::GetLocalizedAll($aData,false,'id,name,');
			$aAssoc = array();
			foreach ($aPaymentType as $iKey => $aValue) {
				$aAssoc[$iKey] = $aValue['name'];
			}
			$aPaymentType = $aAssoc;
		}
		else
			$aPaymentType=Language::GetLocalizedAll($aData);
		Base::$tpl->assign('aPaymentType',$aPaymentType);
		return $aPaymentType;
	}
	//-----------------------------------------------------------------------------------------------
	public function CartOnePageOrder()
	{
		if (Auth::$aUser['type_']=='manager'){
		    MultiLanguage::Redirect('/pages/cart_onepage_order_manager');
		}

		Resource::Get()->Add('/js/user.js',3);
		$_SESSION['current_cart']['delivery_info']=0;
		$_SESSION['current_cart']['id_delivery_type'] = Base::$aRequest['id_delivery_type'];
		$sCheckLoggedError=false;
		$sCheckNewAccountError=false;
		$bDeliveryAddress=true;
		/* hack for fixing back button on end step */
		$_SESSION['is_checked_account']=true;
		$_SESSION['current_cart']['is_confirmed']=0;
		if (!$_SESSION['current_cart']['remark'])$_SESSION['current_cart']['remark']=Base::$aRequest['data']['remark'];

		Cart::AssignPaymentType();

		//подготовка popup
		Base::$aMessageJavascript = array(
		"MakeAuto_select"=> Language::GetMessage("Choose model"),
		"DetailAuto_select"=> Language::GetMessage("Choose year"),
		"add_auto_error"=>Language::GetMessage("error_add_auto"),
		"add_auto_17symbol"=> Language::GetMessage("vin_have_no_17_symbols"),
		"add_auto_model_empty"=> Language::GetMessage("model_and_series_empty"),
		"add_auto_modyfication_empty"=> Language::GetMessage("modyfication_empty"),
		"add_auto_volume_empty"=> Language::GetMessage("volume_empty"),
		);		

		if (Base::$aRequest['is_post']) {
			if (Base::$aRequest['subaction']=='create_new_account'){
			    
			    if(Base::$aRequest['id_np_type']==1 && Base::$aRequest['id_delivery_type']==Base::GetConstant('cart:id_delivery',6)){
			        if (Base::$aRequest['id_delivery_type']==Base::GetConstant('cart:id_delivery',6) &&
			            (!Base::$aRequest['delivery_info']['np_city'] || Base::$aRequest['delivery_info']['np_city'] == 'Выберите город')
			            || (!Base::$aRequest['delivery_info']['np_sklad'] || Base::$aRequest['delivery_info']['np_sklad'] == 'Выберите склад' ))
			                $bDeliveryAddress = false;
			    }

			        
			    //google capcha
			    $capcha = $_POST['g-recaptcha-response'];
			    $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
			    $secret_key = Language::GetConstant('captcha:privat_key','6LdJj9UUAAAAAAEI9b67EP3ZJJhu10MvBgNTJ2bs');
			    $query = $url_to_google_api . '?secret=' . $secret_key . '&response=' . $capcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
			    $data = json_decode(file_get_contents($query));
			    
				$sCheckNewAccountError=$this->NewAccountError();
				if (!Base::$aRequest['data']['name'] /*|| !Base::$aRequest['data']['city']
				|| !Base::$aRequest['data']['address']*/ || !Base::$aRequest['data']['phone'] || !$data->success
				|| $sCheckNewAccountError || !$bDeliveryAddress
				) {
					if ($sCheckNewAccountError) {
						$sError=$sCheckNewAccountError;
					}elseif (!$bDeliveryAddress){
					    $sError="Fill in the delivery address";
					}  else {
						$sError="Please, fill the required fields";
					}
					Base::$tpl->assign('aUser',Base::$aRequest['data']);
				}
				else {
					$aRequestUser=String::FilterRequestData(Base::$aRequest['data'],array('login','password','email'));
					$sSalt=String::GenerateSalt();
					$aRequestUser['password_to_letter'] = $aRequestUser['password'];
					$aRequestUser['password']=String::Md5Salt($aRequestUser['password'],$sSalt);
					$aRequestUser['salt']=$sSalt;
					$aRequestUser['password_temp']='';
					$aRequestUser['is_temp']=0;
					Db::Autoexecute('user',$aRequestUser,'UPDATE',"id='".Auth::$aUser['id']."'");

					$aRequestUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
					'name','country','city','address','address2','zip','phone','phone2','remark'
        			,'additional_field5','additional_field2','additional_field3','additional_field4'
        			,'id_user_customer_type','entity_type','entity_name','additional_field1'
					));
					Db::Autoexecute('user_customer',$aRequestUserCustomer,'UPDATE',"id_user='".Auth::$aUser['id']."'");
					
					// send letter if new user
					$aUser = Db::GetRow("select * from user where id='".Auth::$aUser['id']."'");
					if ($aUser['email'] && $aRequestUser['password_to_letter']) {
						$aManager=Db::GetRow("SELECT um.*, u2.login
							FROM user u
							INNER JOIN user_customer uc ON u.id = uc.id_user
							INNER JOIN user_manager um ON uc.id_manager = um.id_user
							INNER JOIN user u2 ON u2.id = uc.id_manager
							WHERE u.id ='".$aUser['id']."'");
						$sLink="<A href='http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature']."'
								>".Base::$language->getMessage('Confirm')."</a>";
						$sUrl="http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature'];
						$aData=array(
							'info'=>array(
								'link'=>$sLink,
								'url'=>$sUrl,
								'login'=>$aUser['login'],
								'password'=>$aRequestUser['password_to_letter'],
								'email'=>$aUser['email'],
							),
							'aManager'=>$aManager
						);
						$aSmartyTemplate=String::GetSmartyTemplate('confirmation_letter', $aData);
						$sBody=$aSmartyTemplate['parsed_text'];
				
						Mail::AddDelayed($aUser['email'],Base::$language->getMessage('Confirmation Letter'),$sBody,'','',true,2);
					}
					// confirmation-end

					$aCartPackageUpdate['customer_comment']=$aRequestUserCustomer['remark'];
					if (!$_SESSION['current_cart']['id_delivery_type']) $_SESSION['current_cart']['id_delivery_type']=6;
					
					
				   if(Base::$aRequest['id_np_type']==1){
					     unset(Base::$aRequest['delivery_info']['np_address']);
					     $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}else{
					    unset(Base::$aRequest['delivery_info']['np_sklad']); 
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}
					
					Db::AutoExecute('cart_package',$aCartPackageUpdate,'UPDATE'
					," id='".$_SESSION['current_cart_package']['id']."' ".Auth::$sWhere);

					$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
					Base::Redirect("/?action=cart_payment_end&data[id_payment_type]=".$aPaymentType['id']);
				}

			}
			if (Base::$aRequest['subaction']=='check_logged') {
				if (!Base::$aRequest['data']['old_login'] || !Base::$aRequest['data']['old_password'])
				$sCheckLoggedError="Please, enter all the fields";
				else {
					$aOldUser=Auth::IsUser(Base::$aRequest['data']['old_login'], Base::$aRequest['data']['old_password']
					,false,Base::GetConstant('user:is_salt_password',1));
					if ($aOldUser) {
						//Syncronization
						Db::Execute("update cart set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						Db::Execute("update cart_package set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						Db::Execute("update user set visible='0' where id='".Auth::$aUser['id']."'");

						Auth::Login(Base::$aRequest['data']['old_login'], Base::$aRequest['data']['old_password'],false,true
						,Base::GetConstant('user:is_salt_password',1));
						
						Base::Redirect("/?action=cart_onepage_order");
					}
					else
					{
						Base::$tpl->assign('bFromCheckLogged', true);
						$sCheckLoggedError="No user with such login and password";
					}
				}
			}
			if (Base::$aRequest['subaction']=='check_authorized_user') {
			    
			    if (Base::$aRequest['id_delivery_type']==Base::GetConstant('cart:id_delivery',6) && Base::$aRequest['id_np_type']==1){
			    if (
			        (!Base::$aRequest['delivery_info']['np_city'] || Base::$aRequest['delivery_info']['np_city'] == 'Выберите город')
			        || (!Base::$aRequest['delivery_info']['np_sklad'] || Base::$aRequest['delivery_info']['np_sklad'] == 'Выберите склад' ))
			    {
			        $bDeliveryAddress = false;
			    }
			    }
			    
			    
				if ( (($_SESSION['current_cart']['price_delivery'] > 0) && (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['phone'])
				|| (Base::$aRequest['data']['check_order'] == 1 && Base::$aRequest['data']['check_order'] == 0))
				
				|| (($_SESSION['current_cart']['price_delivery'] == 0) && (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['phone']) 
				|| (Base::$aRequest['data']['check_order'] == 1 && Base::$aRequest['data']['own_auto_id'] == 0)) || !$bDeliveryAddress) {
					$sError="Please, fill the required fields";
					Base::$tpl->assign('aUser',Base::$aRequest['data']);
				}
				else {
					$aRequestUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
					'name','country','city','address','address2','zip','phone','phone2',
                        /*'remark',*/'additional_field5','additional_field2','additional_field3','additional_field4'
        			,'id_user_customer_type','entity_type','entity_name','additional_field1'
					));
					//Debug::PrintPre($aRequestUserCustomer);
					$_SESSION['current_cart']['customer_comment']=Base::$aRequest['data']['remark'];
					$_SESSION['current_cart']['is_need_check'] = 0;
					if (isset(Base::$aRequest['check_order']))
						$_SESSION['current_cart']['is_need_check'] = Base::$aRequest['check_order'];
					
					$_SESSION['current_cart']['own_auto_id'] = 0;
					if (isset(Base::$aRequest['own_auto_id']))
						$_SESSION['current_cart']['own_auto_id'] = Base::$aRequest['own_auto_id'];
					if (!$_SESSION['current_cart']['id_delivery_type']) $_SESSION['current_cart']['id_delivery_type']=Base::GetConstant('cart:id_delivery',6);

					if(Base::$aRequest['id_np_type']==1){
				       unset(Base::$aRequest['delivery_info']['np_address']);
				       $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}else{
					    unset(Base::$aRequest['delivery_info']['np_sklad']); 
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}
					Db::Autoexecute('user_customer',$aRequestUserCustomer,'UPDATE',"id_user='".Auth::$aUser['id']."'");

					$aRequestUser=String::FilterRequestData(Base::$aRequest['data'],array('email'));
					Db::Autoexecute('user',$aRequestUser,'UPDATE',"id='".Auth::$aUser['id']."'");

					$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
                    //Base::Redirect("/?action=cart_payment_end&data[id_payment_type]=".$aPaymentType['id']);
                    MultiLanguage::Redirect("/pages/cart_payment_end/?data[id_payment_type]=".$aPaymentType['id']);
				}
			}			
		}


		//order table section
		$sUserCartSql=Base::GetSql("Part/Search",array(
		"type_"=>'cart',
		"where"=> " and c.id_user='".Auth::$aUser['id']."'",
		));
		$aUserCart=Db::GetAll($sUserCartSql);
		if ($aUserCart) foreach ($aUserCart as $iKey => $aValue) {
			$dSubtotal+=$aValue['number']*Currency::PrintPrice($aValue['price']);
			$aUserCart[$iKey]['number_price'] = $aValue['number']*Currency::PrintPrice($aValue['price']);
		}
        Cart::CallParseCart($aUserCart);
		Base::$tpl->assign('aUserCart',$aUserCart);
		
		//Добавление методов доставки в шаблон
		$this->AssignDeliveryMethods();

		//--------------------- nova poshta begin --------------------------
	    
	    $aCityTmp=DB::GetAssoc("SELECT id_np, city_ru as city FROM np_cities_new");
	    $aCityTmpNP=array_combine(array_values($aCityTmp),array_values($aCityTmp));
	    $aCities=array_merge([0=>'Выберите город'],$aCityTmpNP);
		
		$_SESSION['nova_poshta']['city']=$aCityTmp;
		sort($aCities);
		
		Base::$tpl->assign('aCities',$aCities);
// 		}
		//--------------------- nova poshta end --------------------------

		Base::$tpl->assign('dSubtotal',$dSubtotal);
		Base::$tpl->assign('dTotal',$dSubtotal+Currency::PrintPrice($_SESSION['current_cart']['price_delivery']));	
		
		/* in top function - AssignPaymentType 
		Base::$tpl->assign('aPaymentType',Db::GetAll(Base::GetSql('PaymentType',array(
		'where'=>' and pt.visible=1',
		'order'=>' order by pt.num',
		))));*/
		
		Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
		    '1'=>Language::GetMessage('частное лицо'),
		    '2'=>Language::GetMessage('юридическое лицо')
		));
		$aEntityType=explode(",",Language::GetConstant('user:entity_type', 'ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
		Base::$tpl->assign('aEntityType',$aEntityType);

		//select auto section
		$iCountAuto = Db::GetOne("Select count(*) from user_auto where id_user=".Auth::$aUser['id']);
		Base::$tpl->assign('iCountAuto',$iCountAuto);
		Base::$tpl->assign('error_field_auto',Language::GetMessage('Your set check order. Please fill field auto.'));
		if (Customer::IsTempUser(Auth::$aUser['login']))
		{
			
			$aData=array(
			'sWidth'=>"400px;",
			'sHeader'=>"method=post",
			'sContent'=>Base::$tpl->fetch('cart/cart_onepage_check_new_user.tpl'),
			'sSubmitButton'=>'Create and process',
			'sSubmitAction'=>'cart_onepage_order',
			'sError'=>$sError,
			'sHidden'=>" <input type=hidden name=subaction value='create_new_account' />",
			);
			$oForm=new Form($aData);
			Base::$tpl->assign('sCheckNewAccountForm',$oForm->getForm());
            
// 			unset($aField);
// 			$aField['old_login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['data']['old_login'],'name'=>'data[old_login]','szir'=>1);
// 			$aField['old_password']=array('title'=>'Password','type'=>'password','name'=>'data[old_password]','szir'=>1,'class'=>'input');
// 			$aField['remember_me']=array('title'=>'Remember me','type'=>'checkbox','name'=>'remember_me','value'=>1,'class'=>'no');
			
			$aData=array(
			'sWidth'=>"400px;",
			'sHeader'=>"method=post",
			'sContent'=>Base::$tpl->fetch('cart/form_check_logged.tpl'),
// 			'aField'=>$aField,
// 			'bType'=>'generate',
			'sSubmitButton'=>'Login and process',
			'sSubmitAction'=>'cart_onepage_order',
			'sError'=>$sCheckLoggedError,
			'sHidden'=>" <input type=hidden name=subaction value='check_logged' />",
			);
			$oForm=new Form($aData);
			Base::$tpl->assign('sCheckLoggedForm',$oForm->getForm());
		}
		else
		{
			Base::$tpl->assign('aUser',Auth::$aUser);
			foreach ($aEntityType as $aItem)
			    $aEntityTypeOptions[$aItem]=$aItem;
			
			$aData=array(
			'sWidth'=>"400px;",
			'sHeader'=>"method=post",
			'sContent'=>Base::$tpl->fetch('cart/cart_onepage_shipment_detail.tpl'),
			//'sSubmitButton'=>'Update and pay',
			'sSubmitButton'=>'Save and process',
			'sSubmitAction'=>'cart_onepage_order',
			'sError'=>$sCheckLoggedError,
			'sHidden'=>" <input type=hidden name=subaction value='check_authorized_user' />",
			);
			$oForm=new Form($aData);
			Base::$tpl->assign('sCheckNewAccountForm',$oForm->getForm());
		}

		Base::$oContent->AddCrumb('Оформление заказа');
		Base::$sText.=Base::$tpl->fetch("cart/cart_onepage_order.tpl");
	}	
	//--------------------------------------------------------------------------------------
	public function ChangeCity() {

	    if(Base::$aRequest['page']){
            Base::$oResponse->AddScript("$('#np_sklad2').select2('destroy');");
            
            Base::$oResponse->addScript("$('#np_sklad2').parent().html(\"<div id='np_sklad2'></div>\");");
	    } else {
	        Base::$oResponse->AddScript("$('#np_sklad').select2('destroy');");
	        
	        Base::$oResponse->addScript("$('#np_sklad').parent().html(\"<div id='np_sklad'></div>\");");
	    }

	    if(Base::$aRequest['city']) {
            include_once SERVER_PATH."/single/NovaPoshtaApi2.php";
	        include_once SERVER_PATH."/single/NovaPoshtaApi2Areas.php";
	        $oNP = new NovaPoshtaApi2(Base::GetConstant('novaposhta:key'));
	
	         
// 	        $senderCity=$oNP->getCities2(Base::$aRequest['oblast']);
// 	        $cities = $oNP->getCities();
	        
	        
// 	        foreach ($cities['data'] as $aValue){
// 	            Db::Execute("insert ignore into np_cities_new (city,city_ru,id_area, id_np)
		
// 			    values ('".$aValue['Description']."',
// 			        '".$aValue['DescriptionRu']."',
// 			        '".$aValue['CityID']."',
// 			        '".$aValue['Ref']."'
// 			            )");
// 	        }
	        
	        $aWarehouse=array();
	        $aWarehouse[0]="Выберите склад";
	
	        $sCity=Base::$aRequest['city'];
	        $sCityRef= array_search($sCity, $_SESSION['nova_poshta']['city']);
	
	        $aWarehouses=$oNP->getWarehouses($sCityRef);
	        if($aWarehouses['success']) {
	            foreach ($aWarehouses['data'] as $aValue) {
	                $iPos=strpos($aValue['Description'], "Поштомат");
	                if($iPos===false) {
	                    //отделения
	                    $aWarehouse[$aValue['Description']]=$aValue['Description'];
	                } else {
	                    //почтоматы
	                    $aPostomat[$aValue['Description']]=$aValue['Description'];
	                }
	            }
	        }
	        
	        $_REQUEST['page']=Base::$aRequest['page'];
	        Base::$tpl->assign('aWarehouse',$aWarehouse);
	        $sOption=Base::$tpl->fetch('cart/select_np_sklad.tpl');
	        
	        if(Base::$aRequest['page']){
	            Base::$oResponse->AddAssign('np_sklad'.Base::$aRequest['page'],'outerHTML',$sOption);
	            Base::$oResponse->AddScript("$('#np_sklad".Base::$aRequest['page']."').on('change', function() {xajax_process_browse_url('./?action=cart_change_sklad&page=".Base::$aRequest['page']."&city='+$(this).val()+'&oblast='+$('#np_oblast".Base::$aRequest['page']."').val()+'&sklad='+$('#np_sklad".Base::$aRequest['page']."').val())});");
                Base::$oResponse->AddScript("$('#np_sklad2').select2({width: '250px'});");
	        }else{
	            Base::$oResponse->AddAssign('np_sklad'.Base::$aRequest['page'],'outerHTML',$sOption);
	            Base::$oResponse->AddScript("$('#np_sklad".Base::$aRequest['page']."').on('change', function() {xajax_process_browse_url('./?action=cart_change_sklad&page=".Base::$aRequest['page']."&city='+$(this).val()+'&oblast='+$('#np_oblast".Base::$aRequest['page']."').val()+'&sklad='+$('#np_sklad".Base::$aRequest['page']."').val())});");
                Base::$oResponse->AddScript("$('#np_sklad').select2({width: '250px'});");
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CartOnePageOrderManager()
	{
		if (Auth::$aUser['type_']!='manager'){
		    MultiLanguage::Redirect('/pages/cart_check_account');
		}
		Resource::Get()->Add('/js/user.js',3);
		
		$_SESSION['current_cart']['is_confirmed']=0;
		$_SESSION['current_cart']['delivery_info']=0;

		$_SESSION['current_cart']['id_delivery_type'] = Base::$aRequest['id_delivery_type'];
        $aName=Cart::GetUsersForFilter();
        
		$sCheckLoggedError=false;
		$sCheckNewAccountError=false;
		$bDeliveryAddress = true;
		
		if (Base::$aRequest['is_post']) {
			if (Base::$aRequest['subaction']=='create_new_account'){
				$sCheckNewAccountError=$this->NewAccountManagerError();
				
				if (Base::$aRequest['id_delivery_type']==Base::GetConstant('cart:id_delivery',6) && 
				    (!Base::$aRequest['delivery_info']['np_city'] || Base::$aRequest['delivery_info']['np_city'] == 'Выберите город')
				    || (!Base::$aRequest['delivery_info']['np_sklad'] || Base::$aRequest['delivery_info']['np_sklad'] == 'Выберите склад' ))
				        $bDeliveryAddress = false;
				
				if (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['phone'] 
				|| $sCheckNewAccountError || !$bDeliveryAddress
				) {
					if ($sCheckNewAccountError) {
						$sError=$sCheckNewAccountError;
					} elseif (!$bDeliveryAddress){
					    $sError="Fill in the delivery address";
					} else {
						$sError="Please, fill the required fields";
					}
					Base::$tpl->assign('aUser',Base::$aRequest['data']);
				}
				else {
					$aRequestUser=String::FilterRequestData(Base::$aRequest['data'],array('login','password','email'));
					Base::$aRequest['login']=$aRequestUser['login'];
					Base::$aRequest['password']=$aRequestUser['password'];
					Base::$aRequest['email']=$aRequestUser['email'];

					$_SESSION['current_cart_package']['new_user']=User::DoNewAccount(true);
// 					$_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];

					if(Base::$aRequest['id_np_type']==1){
					    unset(Base::$aRequest['delivery_info']['np_address']);
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}else{
					    unset(Base::$aRequest['delivery_info']['np_sklad']);
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}
					
					// recalc cart
					User::RecalcCart($_SESSION['current_cart_package']['new_user'],1);
				    Db::Execute("update user set is_temp=0 where id='".$_SESSION['current_cart_package']['new_user']."' ");
					
					$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
					MultiLanguage::Redirect("/pages/cart_payment_end/?data[id_payment_type]=".$aPaymentType['id']);
				}

			}
			if (Base::$aRequest['subaction']=='select_account') {
				$bOk=(Base::$aRequest['data']['old_login']>0 || Base::$aRequest['data']['old_name']>0);
				if(Base::$aRequest['data']['old_login']>0 && Base::$aRequest['data']['old_name']>0
				&& Base::$aRequest['data']['old_login']!=Base::$aRequest['data']['old_name']) $bOk=FALSE;
				if ($bOk)
				{
					if ($aOldUser) {
						//Syncronization
						//Db::Execute("update cart set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						//Db::Execute("update cart_package set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						//Db::Execute("update user set visible='0' where id='".Auth::$aUser['id']."'");

						//if(Auth::IsAuth()) Cart::RefreshCartPackage(Auth::$aUser['id']);
						MultiLanguage::Redirect("/pages/cart_shipment_detail");
					}
					if(Base::$aRequest['data']['old_login']>0)$_SESSION['current_cart_package']['new_user']=Base::$aRequest['data']['old_login'];
					else $_SESSION['current_cart_package']['new_user']=Base::$aRequest['data']['old_name'];

// 					$_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					if(Base::$aRequest['id_np_type']==1){
					    unset(Base::$aRequest['delivery_info']['np_address']);
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}else{
					    unset(Base::$aRequest['delivery_info']['np_sklad']);
					    $_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];
					}
					
					
					Cart::RecalcCartUser(Auth::$aUser['id_user'],$_SESSION['current_cart_package']['new_user']);
					//$_SESSION['current_cart']['delivery_info']=Base::$aRequest['delivery_info'];

					$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
					MultiLanguage::Redirect("/pages/cart_payment_end/?data[id_payment_type]=".$aPaymentType['id']);
				}else
				$sCheckLoggedError="Please, enter all the fields";
			}
		}

		$aData=array(
			'table'=>'delivery_type',
			'where'=>" and t.visible=1",
		);
		$aDeliveryType=Language::GetLocalizedAll($aData);
		//$aDeliveryType=Db::GetRow(Base::GetSql('DeliveryType'));
		if ($aDeliveryType) {
		    $_SESSION['current_cart']['id_delivery_type']=$aDeliveryType['id'];
		    $_SESSION['current_cart']['price_delivery']=$aDeliveryType['price'];
		}

		$_SESSION['is_checked_account']=true;
		if(!Base::$aRequest['data']['login']) $_REQUEST['data']['login']='m'.Auth::GenerateLogin();
		if(!Base::$aRequest['data']['password']) $_REQUEST['data']['password']=Auth::GeneratePassword();
		if(!Base::$aRequest['data']['verify_password']) $_REQUEST['data']['verify_password']=$_REQUEST['data']['password'];

		$sUserCartSql=Base::GetSql("Part/Search",array(
		"type_"=>'cart',
		"where"=> " and c.id_user='".Auth::$aUser['id']."'",
		));
		$aUserCart=Db::GetAll($sUserCartSql);
		if ($aUserCart) foreach ($aUserCart as $iKey => $aValue) {
			$dSubtotal+=$aValue['number']*Currency::PrintPrice($aValue['price']);
			$aUserCart[$iKey]['number_price'] = $aValue['number']*Currency::PrintPrice($aValue['price']);
		}
		Cart::CallParseCart($aUserCart);
		Base::$tpl->assign('aUserCart',$aUserCart);
		
		Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
		    '1'=>Language::GetMessage('частное лицо'),
		    '2'=>Language::GetMessage('юридическое лицо')
		));
		$aEntityType=explode(",",Language::GetConstant('user:entity_type', 'ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
		Base::$tpl->assign('aEntityType',$aEntityType);
		
		//Добавление методов доставки в шаблон
		Cart::AssignDeliveryMethods();

		//--------------------- nova poshta begin --------------------------

		$aCityTmp=DB::GetAssoc("SELECT id_np, city_ru as city FROM np_cities_new");
	    $aCityTmpNP=array_combine(array_values($aCityTmp),array_values($aCityTmp));
	    $aCities=array_merge([0=>'Выберите город'],$aCityTmpNP);
		
		$_SESSION['nova_poshta']['city']=$aCityTmp;
		sort($aCities);
		
		Base::$tpl->assign('aCities',$aCities);
		
		
// 		}
		//--------------------- nova poshta end --------------------------

		//Добавление методов оплаты в шаблон
		Cart::AssignPaymentType();
		
		Base::$tpl->assign('dSubtotal',$dSubtotal);
		Base::$tpl->assign('dTotal',$dSubtotal+Currency::PrintPrice($_SESSION['current_cart']['price_delivery']));
		    
		$aData=array(
		'sWidth'=>"450px;",
		'sHeader'=>"method=post",
// 		'sTitle'=>"Create New account",
		'sContent'=>Base::$tpl->fetch('cart/cart_onepage_select_new_account.tpl'),
		'sSubmitButton'=>'Create and process',
		'sSubmitAction'=>'cart_onepage_order_manager',
		'sError'=>$sError,
		'sHidden'=>" <input type=hidden name=subaction value='create_new_account' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckNewAccountForm',$oForm->getForm());
		
		$aData=Base::$tpl->get_template_vars('aData');
		
		$aData=array(
		'sWidth'=>"420px;",
		'sHeader'=>"method=post",
// 		'sTitle'=>"Select account",
		'sContent'=>Base::$tpl->fetch('cart/cart_onepage_select_account.tpl'),
		'sSubmitButton'=>'process',
		'sSubmitAction'=>'cart_onepage_order_manager',
		'sError'=>$sCheckLoggedError,
		'sHidden'=>" <input type=hidden name=subaction value='select_account' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckLoggedForm',$oForm->getForm());		

		Base::$sText.=Base::$tpl->fetch("cart/cart_onepage_order.tpl");
		
		Base::$oContent->AddCrumb('Оформление заказа');
	}
	//-----------------------------------------------------------------------------------------------
	public function CartPrint()
	{
		$aCart=Db::GetAll("select * from cart where type_='cart' ".Auth::$sWhere." order by post_date desc");
		if (!$aCart) Base::Redirect('?action=cart_cart&table_error=cart_not_found');

		Base::$tpl->assign('aCart',$aCart);
		$dPriceTotal = 0;
		foreach ($aCart as $iKey => $aValue) {
		    $dPriceTotal+=Currency::PrintPrice($aValue['price'],0,2,"<none>")*$aValue['number'];
		}
		//Base::$tpl->assign('dSubtotal',Base::$db->getOne("select sum(number*price) from cart where type_='cart' ".Auth::$sWhere));
		Base::$tpl->assign('dSubtotal',$dPriceTotal);
		
		PrintContent::Append(Base::$tpl->fetch('cart/cart_print.tpl'));
		Base::Redirect('?action=print_content&return=cart_cart');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseCart(&$aItem)
	{
	    $aRowBrand=array();
	    
		if ($aItem) foreach($aItem as $key => $value) {
			$aItem[$key]['total']=$value['number'] * Currency::PrintPrice($value['price'],null,2,'<none>');

			$aItem[$key]['cart_history']=Db::GetAll(
    			Base::GetSql('CartHistory',array(
    			'code'=>$value['code'],
    			'make'=>$value['make'],
    			'id_provider'=>$value['id_provider'],
    			'order'=>' order by post DESC',
    			'limit'=>' limit 0,3',
			)));

			if (!$aItem[$key]['is_archive']) {
				$dSubtotal+=$value['number'] * Currency::PrintPrice($value['price'],null,2,'<none>');
				$dSubtotalWeight+=$value['number']*$value['weight'];
			}
			
			if(!$aRowBrand[$value['pref']]) {
			    $aRowBrand[$value['pref']]=Db::GetRow("select *,
			    	if(image!='' and is_use_own_logo=1,image,if(image_tecdoc<>'',concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , image_tecdoc),image)) as image_logo 
			        from cat where pref='".$value['pref']."' ");
			}
			
			if($aRowBrand[$value['pref']]) {
			    $aItem[$key]['brand']=$aRowBrand[$value['pref']]['title'];
			    $aItem[$key]['image_logo']=$aRowBrand[$value['pref']]['image_logo'];
			}
		}
		
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
		),$this->aCats);
		
		if ($aItem) {
		    foreach ($aItem as $sKey => $aValue) {
		        $aItem[$sKey]['image']=$aArtIds[mb_strtoupper($aValue['item_code'],'utf-8')]['img_path'];
		    }
		}
		//end images
		
		return array('dSubtotal'=>$dSubtotal,'dSubtotalWeight'=>$dSubtotalWeight);
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCartItemChecked()
	{
		if (Base::$aRequest['row_check']) {
			foreach (Base::$aRequest['row_check'] as $value) {
				list(Base::$aRequest['item_code'],Base::$aRequest['id_provider'])=explode('::',$value);

				if (Base::$aRequest['item_code'] && Base::$aRequest['id_provider'] )
				$this->AddCartItem(Base::$aRequest['n'][$value],false,Base::$aRequest['r'][$value]);
			}
		}
		Base::Redirect('/?action=cart_cart');
	}
	//-----------------------------------------------------------------------------------------------
	public function AddCartItem($iNumber=1,$bRedirect=true,$sReference='')
	{
        if ($iNumber <= 0)
            $iNumber = 1;
        if ((int) Base::$aRequest['number'] > 0)
            $iNumber = (int) Base::$aRequest['number'];
        if (Base::$aRequest['reference'])
            $sReference = Base::$aRequest['reference'];
        
        $a = Db::GetRow(Base::GetSql('Catalog/Price', array(
            'id_provider' => Base::$aRequest['id_provider'],
            'aItemCode' => array(
                Base::$aRequest['item_code']
            ),
            'id_part' => Base::$aRequest['id_part'],
            'customer_discount' => Discount::CustomerDiscount(Auth::$aUser)
            
        )));
        
        
        if (!$a || $a['price'] == 0) {
            if ($bRedirect)
                Base::Redirect('?action=cart_cart&table_error=cart_not_found');
            else
                return;
        }
        
        $a['zzz_code']            = $a['id'];
        $a['id_currency_user']    = (Auth::$aUser['id_currency'] ? Auth::$aUser['id_currency'] : 1);
        $a['price_currency_user'] = Currency::PrintPrice($a['price'], Auth::$aUser['id_currency'], 2, "<none>") * $iNumber;
        
        unset($a['id']);
        unset($a['post_date']);
        
        $a['id_user']                    = Auth::$aUser['id'];
        $a['session']                    = session_id();
        $a['number']                     = $iNumber;
        $a['customer_id']                = mysql_real_escape_string($sReference);
        $a['price_parent_margin']        = $a['price_original'] * Auth::$aUser['parent_margin'] / 100;
        $a['price_parent_margin_second'] = $a['price_original'] * Auth::$aUser['parent_margin_second'] / 100;
        $a['id_provider_ordered']        = $a['id_provider'];
        $a['provider_name_ordered']      = $a['provider_name'];
        //$a['price']=Currency::GetPriceWithoutSymbol($a['price']);
        //$a['price_order']=Currency::GetPriceWithoutSymbol($a['price_order']);
        
        if (Base::$aRequest['in_basket'] == 1) {
            $aExistingCart = Db::GetRow(Base::GetSql("Part/Search", array(
                "type_" => 'cart',
                "where" => " and c.id_user='" . Auth::$aUser['id'] . "' and c.item_code='" . $a['item_code'] . "'
    			and c.id_provider='" . $a['id_provider'] . "'"
            )));
            
            if ($aExistingCart) {
                $iNewNumber = $aExistingCart['number'] + $a['number'];
                Db::AutoExecute('cart', array(
                    'number' => $iNewNumber
                ), 'UPDATE', " id='" . $aExistingCart['id'] . "'");
            } else
            ///Добавляем в корзину
                Db::AutoExecute("cart", $a);
            
            if (Base::$aRequest['xajax_request']) {
                $bRedirect = false;
                Base::$oResponse->AddScript("
    			     $('#" . Base::$aRequest['link_id'] . "').find('a').addClass('inbasket');    
    		    ");
            }
            if ($bRedirect)
                Base::Redirect('?action=cart_cart');
            
            $oContent = new Content();
            $oContent->ParseTemplate(true);
            
            Cart::ShowPopupCart();
        } else {
            Base::$aRequest['fast_order']['phone'] = Catalog::StripCode(Base::$aRequest['fast_order']['phone']);
            
            $sManagerlogin = Db::GetOne("select login from user where type_='manager' and login='" . Base::$aRequest['fast_order']['phone'] . "'");
            
            if (!Base::$aRequest['fast_order']['phone'] || !preg_match('/(067|068|096|097|098|050|066|095|099|063|073|093|091|092|094)([0-9]{7})/', Base::$aRequest['fast_order']['phone']) || $sManagerlogin == Base::$aRequest['fast_order']['phone']) {
                Base::$oResponse->addAssign('check_phone', 'innerHTML', Language::GetText('Incorrect phone format.'));
                Base::$oResponse->addScript("$('#user_phone1').val('');$('#check_phone').show();");
            }elseif(!Base::$aRequest['fast_order']['name']){
                Base::$oResponse->addAssign('check_phone', 'innerHTML', Language::GetText('Please write your name.'));
            }else {
                Db::AutoExecute("cart", $a);
                $iIdCart = Db::InsertID();
                Cart::OrderOneClick(Base::$aRequest['fast_order'], $iIdCart);
                return;
            }
        }
    }
	//-----------------------------------------------------------------------------------------------
	public function CartUpdateNumber()
	{
		if (Base::$aRequest['number']>0) {
			Base::$db->Execute("update cart set number='".Base::$aRequest['number']."' where type_='cart'
			and id='".Base::$aRequest['id']."' ".Auth::$sWhere);

			$aCart=Db::GetRow("select * from cart where id='".Base::$aRequest['id']."'");
			if ($aCart) {
				$iPrice_currency_user = $aCart['number'] * Base::$oCurrency->PrintPrice($aCart['price'],$aCart['id_currency_user'],2,"<none>");
				Base::$db->Execute("update cart set price_currency_user='".$iPrice_currency_user."' where type_='cart'
					and id='".Base::$aRequest['id']."' ".Auth::$sWhere);
				
				Base::$oResponse->addAssign('cart_total_'.$aCart['id'],'innerHTML'
				,Base::$oCurrency->PrintSymbol($aCart['number']*Base::$oCurrency->PrintPrice($aCart['price'],null,2,"<none>")));

				//$dSubTotal=Base::$db->getOne("select sum(number*price) from (".$_SESSION['cart']['table_sql'].") sc ");/
				$aCartList=Db::GetAll($_SESSION['cart']['table_sql']);
				if ($aCartList) foreach ($aCartList as $aValue) {
					$dSubTotal+=$aValue['number']*Base::$oCurrency->PrintPrice($aValue['price'],null,2,"<none>");
					$dSubTotalWeight+=$aValue['number']*$aValue['weight'];
				}
				Base::$oResponse->addAssign('cart_subtotal','innerHTML',Base::$oCurrency->PrintSymbol($dSubTotal));
				Base::$oResponse->addAssign('cart_subtotal_weight','innerHTML',$dSubTotalWeight);
				Base::$oResponse->AddAssign('icart_total_id','innerHTML',Base::$oCurrency->PrintSymbol($dSubTotal));
			}
		}
		elseif(Base::$aRequest['number']){
			Base::$oResponse->addAlert(Base::$language->getMessage('Error: not valid number.'));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderList()
	{
		Base::$aTopPageTemplate=array('panel/tab_customer_cart.tpl'=>'order');

		Base::$tpl->assign_by_ref("oCatalog", new Catalog());

		if (Base::$aRequest['is_post']) {

			//[----- UPDATE -----------------------------------------------------]
			$sQuery="update cart set
						customer_comment='".htmlspecialchars(strip_tags(Base::$aRequest['customer_comment']))."',
						customer_id='".htmlspecialchars(strip_tags(Base::$aRequest['customer_id']))."'
					where id='".Base::$aRequest['id']."' and type_='order' ".Auth::$sWhere;
			//[----- END UPDATE -------------------------------------------------]
			Base::$db->Execute($sQuery);
			//Form::AfterReturn('cart_order');
			Base::Redirect('/pages/cart_order/');
			
		}

		if ( Base::$aRequest['action']=='cart_order_edit') {
			Form::BeforeReturn('cart_order','cart_order_edit');

			if (Base::$aRequest['action']=='cart_order_edit') {
				$aUserCart=Db::GetRow("select * from cart where id='".Base::$aRequest['id']."'
					and type_='order'	".Auth::$sWhere);
				Base::$tpl->assign('aData',$aUserCart);
			}

			$aField['customer_comment']=array('title'=>'Customer comment','type'=>'textarea','name'=>'customer_comment','value'=>$aUserCart['customer_comment']);
			$aField['customer_id']=array('title'=>'Customer Database ID','type'=>'input','name'=>'customer_id','value'=>$aUserCart['customer_id']);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cart item",
// 			'sContent'=>Base::$tpl->fetch('cart/form_order.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'cart_order',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'cart_order',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();

			return;
		}

		Base::$tpl->assign('aCartPackage',Db::GetAll("select * from cart_package where is_archive='0'
			".Auth::$sWhere." order by post_date desc"));
		Base::$tpl->assign('aAccesType',array(
		'own'=>Language::GetMessage('Own orders'),
		'subuser'=>Language::GetMessage('Subuser orders'),
		));
		Base::$tpl->assign('aOrderStatus',$aOrderStatus=array(
		''=>Language::GetMessage('All'),
		'all_except_archive'=>Language::GetMessage('All except Archive'),
		'pending'=>Language::GetMessage('pending'),
		'new'=>Language::GetMessage('new'),
		'work'=>Language::GetMessage('work'),
		'confirmed'=>Language::GetMessage('confirmed'),
		'road'=>Language::GetMessage('road'),
		'store'=>Language::GetMessage('store'),
		'end'=>Language::GetMessage('end'),
		'refused'=>Language::GetMessage('refused'),
		));
		Base::$tpl->assign('aEndable',array(
		''=>Language::GetMessage('All'),
		'1'=>Language::GetMessage('Is Endable'),
		'0'=>Language::GetMessage('Not IsEndable'),
		));

		$aField['code']=array('title'=>'code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
		$aField['name']=array('title'=>'name','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
		$aField['customer_id']=array('title'=>'Customer_ID','type'=>'input','value'=>Base::$aRequest['search']['customer_id'],'name'=>'search[customer_id]');
		$aField['id']=array('title'=>'#','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['id_cart_package']=array('title'=>'Order #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['order_status']=array('title'=>'Status','type'=>'select','options'=>$aOrderStatus,'name'=>'search[order_status]','selected'=>Base::$aRequest['search']['order_status']);
// 		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from'],'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
// 		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to'],'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aData=array(
		'sHeader'=>"method=get",
		'sHint'=>"cart_order_form",
		//'sContent'=>Base::$tpl->fetch('cart/form_order_search.tpl'),
	    'aField'=>$aField,
	    'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'cart_order',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'cart_order',
		'bIsPost'=>0,
		'sWidth'=>"650px",
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		$aData['sSearchForm']=$oForm->getForm();

		// --- search ---
		if (!Base::$aRequest['search']['archive']) $sWhere.=" and c.is_archive='0'";
		if (Base::$aRequest['search']['customer_id']) $sWhere.=" and c.customer_id like
			'%".Base::$aRequest['search']['customer_id']."%'";
		if (Base::$aRequest['search']['code']) $sWhere.=" and c.code='".Base::$aRequest['search']['code']."'";
		if (Base::$aRequest['search']['id_cart_package'])
		$sWhere.=" and c.id_cart_package='".Base::$aRequest['search']['id_cart_package']."'";

		if (Base::$aRequest['search']['order_status']=='all_except_archive') {
			$sWhere.=" and c.order_status in ('pending','new','work','confirmed','road','store')";
		} elseif (Base::$aRequest['search']['order_status']) {
			$sWhere.=" and c.order_status='".Base::$aRequest['search']['order_status']."'";
		}

		if (Base::$aRequest['search']['id']) $sWhere.=" and c.id='".Base::$aRequest['search']['id']."'";
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and UNIX_TIMESTAMP(c.post_date)>='".strtotime(Base::$aRequest['date_from'])."'
				and UNIX_TIMESTAMP(c.post_date)<='".strtotime(Base::$aRequest['date_to']." 23:59:59")."'";
		}
		if (Base::$aRequest['search']['subuser_login']) $sWhere.=" and u.login='".Base::$aRequest['search']['subuser_login']."'";

		if (Base::$aRequest['search']['acces_type']=='own' || !Base::$aRequest['search']['acces_type']) {
			$sWhere.=" and c.id_user='".Auth::$aUser['id']."'";
		}
		else {
			Base::$aTopPageTemplate=array('panel/tab_customer_cart.tpl'=>'subuser_order');
			$aSubuserId=array_keys(Base::$db->GetAssoc(Base::GetSql('Customer/SubuserAssoc',array(
			'id_user'=>Auth::$aUser['id'],
			))));
			$sWhere.=" and c.id_user in(".implode(',',$aSubuserId).")";
		}
		if (Base::$aRequest['search']['is_endable']!='')
		$sWhere.=" and c.is_endable='".Base::$aRequest['search']['is_endable']."'";
		// --------------

		$oTable=new Table();
		$oTable->sWidth='99%';
		$oTable->sSql=Base::GetSql("Part/Search",array(
		"name"=>trim(Base::$aRequest['search']['name']),
		"where"=>$sWhere,
		));

		$_SESSION['customer_order']['current_sql']=$oTable->sSql;

		$oTable->aOrdered="order by c.post_date desc";
		$oTable->aColumn=array(
		   
// 		'id'=>array('sTitle'=>'#'),
// 		'id_cart_package'=>array('sTitle'=>'Order #'),
// 		'code'=>array('sTitle'=>'CartCode'),
// 		'order_status'=>array('sTitle'=>'Order Status'),
// 		'name'=>array('sTitle'=>'Name/Customer_Id'),
// 		'term'=>array('sTitle'=>'Term'),
// 		'number'=>array('sTitle'=>'Number'),
// 		'price'=>array('sTitle'=>'Price'),
// 		'total'=>array('sTitle'=>'Total'),
// 		'action'=>array(),
		
		);
		$oTable->sDataTemplate='cart/row_order.tpl';
// 		$oTable->sButtonTemplate='cart/button_order.tpl';
		$oTable->iRowPerPage=20;
// 		$oTable->bCheckVisible=true;
		$oTable->aCallback=array($this,'CallParseOrder');

		$aData['sOrderItem']=$oTable->getTable("Cart Order Items",'cart_order');

		Base::$tpl->assign('aData',$aData);

		Base::$sText.=Base::$tpl->fetch('cart/order.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseOrder(&$aItem)
	{
		if ($aItem) {
			foreach($aItem as $key => $value) {
				$aOrderId[]=$value['id'];
				/*$aItem[$key]['name']="<b>".$value['name'].
				"</b><br>".String::FirstNwords($value['customer_comment'],5);*/
				$aItem[$key]['total']=$value['number']*Currency::PrintPrice($value['price'],1);
				$aItem[$key]['brand_title']=Db::GetOne("select title from cat where name like '".$value['cat_name']."' ");
				$aItem[$key]['new_brand_title']=Db::GetOne("select title from cat where name like '".$value['new_brand']."' ");
			}
			$aHistory=Db::GetAll("select * from cart_log
				where id_cart in (".implode(',',$aOrderId).")");
			if ($aHistory) foreach($aHistory as $key => $value) {
				$aHistoryHash[$value['id_cart']][]=$value;
			}

			foreach($aItem as $key => $value) {
				if ($aHistoryHash && in_array($value['id'],array_keys($aHistoryHash)) ) {
					$aItem[$key]['history']=$aHistoryHash[$value['id']];
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public static function ParseVinCode($sVinCode, $iCheckVinLen = 17)
	{
		$sParsedVinCode = '';
		$sParsedVinCode = strtoupper($sVinCode);
		$aFindChars = array('I', 'O', 'Q', ' ');
		$aReplChars = array('1', '0', '9', '');
		$sParsedVinCode = str_replace($aFindChars, $aReplChars, $sParsedVinCode);
		if( $iCheckVinLen == strlen($sParsedVinCode) ) {
			return $sParsedVinCode;
		} else {
			// wrong VIN code format! Look at ISO 3779-1983 for more information
			return '';
		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step1 - package confirm
	 */
	public function PackageConfirm()
	{
		Base::$tpl->assign('iPathStep',1);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");
		if (!$_SESSION['current_cart']['id_delivery_type']) $_SESSION['current_cart']['id_delivery_type']=1;

		/* hack for fixing back button on end step */
		$_SESSION['current_cart']['is_confirmed']=0;

		$sUserCartSql=Base::GetSql("Part/Search",array(
		"type_"=>'cart',
		"where"=> " and c.id_user='".Auth::$aUser['id']."'",
		));
		$aUserCart=Db::GetAll($sUserCartSql);
		if ($aUserCart) foreach ($aUserCart as $iKey => $aValue) {
			$dSubtotal+=$aValue['number']*Currency::PrintPrice($aValue['price']);
			$aUserCart[$iKey]['number_price'] = $aValue['number']*Currency::PrintPrice($aValue['price']);
		}
		Base::$tpl->assign('aUserCart',$aUserCart);
		
		Cart::AssignDeliveryMethods();
		/*Base::$tpl->assign('aDeliveryType',Db::GetAll(Base::GetSql('DeliveryType',array('visible'=>1))));
		$aDeliveryTypeRow=Db::GetRow(Base::GetSql('DeliveryType',array(
		'id'=>$_SESSION['current_cart']['id_delivery_type'],
		'visible'=>1,
		)));
		Base::$tpl->assign('aDeliveryTypeRow',$aDeliveryTypeRow);*/

		Base::$tpl->assign('dSubtotal',$dSubtotal);
		Base::$tpl->assign('dTotal',$dSubtotal+Currency::PrintPrice($_SESSION['current_cart']['price_delivery']));


		Base::$sText.=Base::$tpl->fetch('cart/package_confirm.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function PackageDelete()
	{
		$aCartPackage=Db::GetRow("select * from cart_package where id='".Base::$aRequest['id']."' ".Auth::$sWhere);
		if (!$aCartPackage || $aCartPackage['order_status']!='pending')
		Base::Redirect("/?action=cart_package_list&table_error=Error_deleting_package");

		Db::Execute("update cart set type_='cart',id_cart_package=0
			where id_cart_package='".Base::$aRequest['id']."' ".Auth::$sWhere);
		Db::Execute("delete from cart_package_log cpl
		    inner join cart_package cp on cp.id = cpl.id_cart_package
		    where cpl.id_cart_package='".Base::$aRequest['id']."' ".Auth::$sWhere);
		Db::Execute("delete from cart_package where id='".Base::$aRequest['id']."' ".Auth::$sWhere);

		if (Base::$aRequest['return_action']) $sReturnAction=Base::$aRequest['return_action'];
		else $sReturnAction='cart_package_list';

		Base::Redirect("/?action=".$sReturnAction);
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step2
	 */
	public function CheckAccount()
	{
		if (Auth::$aUser['type_']=='manager') Base::Redirect('/?action=cart_select_account');
		if (!Customer::IsTempUser(Auth::$aUser['login'])) Base::Redirect('/?action=cart_shipment_detail');
		Base::$tpl->assign('iPathStep',2);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");

		$sCheckLoggedError=false;
		$sCheckNewAccountError=false;

		if (Base::$aRequest['is_post']) {
			if (Base::$aRequest['subaction']=='create_new_account'){
				$sCheckNewAccountError=$this->NewAccountError();
				if (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['city']
				|| !Base::$aRequest['data']['address'] || !Base::$aRequest['data']['phone']
				|| $sCheckNewAccountError
				) {
					if ($sCheckNewAccountError) {
						$sError=$sCheckNewAccountError;
					} else {
						$sError="Please, fill the required fields";
					}
					Base::$tpl->assign('aUser',Base::$aRequest['data']);
				}
				else {
					$aRequestUser=String::FilterRequestData(Base::$aRequest['data'],array('login','password','email'));
					$sSalt=String::GenerateSalt();
					$aRequestUser['password']=String::Md5Salt($aRequestUser['password'],$sSalt);
					$aRequestUser['salt']=$sSalt;
					$aRequestUser['password_temp']='';
					Db::Autoexecute('user',$aRequestUser,'UPDATE',"id='".Auth::$aUser['id']."'");

					$aRequestUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
					'name','country','city','zip','company','address','phone','remark'
					));
					Db::Autoexecute('user_customer',$aRequestUserCustomer,'UPDATE',"id_user='".Auth::$aUser['id']."'");

					$aCartPackageUpdate['customer_comment']=$aRequestUserCustomer['remark'];
					Db::AutoExecute('cart_package',$aCartPackageUpdate,'UPDATE'
					," id='".$_SESSION['current_cart_package']['id']."' ".Auth::$sWhere);
					
					// send letter
					$aUser=Db::GetRow("SELECT * from user where id = ".Auth::$aUser['id']);
					$aManager=Db::GetRow("SELECT um.*, u2.login
					FROM user u
					INNER JOIN user_customer uc ON u.id = uc.id_user
					INNER JOIN user_manager um ON uc.id_manager = um.id_user
					INNER JOIN user u2 ON u2.id = uc.id_manager
					WHERE u.id ='".Auth::$aUser['id']."'");
					$sLink="<A href='http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature']."'
						>".Base::$language->getMessage('Confirm')."</a>";
					$sUrl="http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature'];
						
					$aData=array(
							'info'=>array(
									'link'=>$sLink,
									'url'=>$sUrl,
									'login'=>Base::$aRequest['data']['login'],
									'password'=>Base::$aRequest['data']['password'],
									'email'=>Base::$aRequest['data']['email'],
							),
							'aManager'=>$aManager
					);
					$aSmartyTemplate=String::GetSmartyTemplate('confirmation_letter', $aData);
					$sBody=$aSmartyTemplate['parsed_text'];
						
					Mail::AddDelayed(Base::$aRequest['data']['email'],Base::$language->getMessage('Confirmation Letter'),$sBody,'','',true,2);
					
					if(Language::getConstant('manager_send_mail_for_new_user',1)) {
						$aData=array(
								'info'=>array(
										'login'=>Base::$aRequest['data']['login'],
										'email'=>Base::$aRequest['data']['email'],
								),
						);
						$aSmartyTemplate=String::GetSmartyTemplate('manager_create_new_customer', $aData);
						$sBody=$aSmartyTemplate['parsed_text'];
					
						Mail::AddDelayed(Language::getConstant('global:to_email'),$aSmartyTemplate['name'].' - '.Base::$aRequest['login'],$sBody,'','',true,2);
					}
						
					Base::Redirect("/?action=cart_payment_method");
				}

			}
			if (Base::$aRequest['subaction']=='check_logged') {
				if (!Base::$aRequest['data']['old_login'] || !Base::$aRequest['data']['old_password'])
				$sCheckLoggedError="Please, enter all the fields";
				else {
					$aOldUser=Auth::IsUser(Base::$aRequest['data']['old_login'], Base::$aRequest['data']['old_password']
					,false,Base::GetConstant('user:is_salt_password',1));
					if ($aOldUser) {
						//Syncronization
						Db::Execute("update cart set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						Db::Execute("update cart_package set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						Db::Execute("update user set visible='0' where id='".Auth::$aUser['id']."'");

						Auth::Login(Base::$aRequest['data']['old_login'], Base::$aRequest['data']['old_password'],false,true
						,Base::GetConstant('user:is_salt_password',1));
						Base::Redirect("/?action=cart_shipment_detail");
					}
					else $sCheckLoggedError="No user with such login and password";
				}
			}
		}

		$_SESSION['is_checked_account']=true;

		Resource::Get()->Add('/single/language_js.php');
		
		$aField['login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['data']['login'],'name'=>'data[login]','szir'=>1);
		$aField['password']=array('title'=>'Password','type'=>'password','value'=>Base::$aRequest['data']['password'],'name'=>'data[password]','szir'=>1);
		$aField['verify_password']=array('title'=>'Retype Password','type'=>'password','value'=>Base::$aRequest['data']['verify_password'],'name'=>'data[verify_password]','szir'=>1);
		$aField['email']=array('title'=>'Email','type'=>'input','value'=>Base::$aRequest['data']['email'],'name'=>'data[email]','szir'=>1);
	
		$aField=array_merge($aField,$this->NewAccountDeliveryInfoFields());
		
	    $aField['user_agreement']=array('type'=>'checkbox','name'=>'data[user_agreement]','value'=>'1','checked'=>Base::$aRequest['data']['user_agreement'],'colspan'=>2,'add_to_td'=>array(
	        'i_agree_to'=>array('type'=>'text','value'=>Language::GetMessage('I agree to')." <a href='/pages/agreement' target=_blank> ".Language::GetMessage('User agreement')."</a>")
	    ));
		
		$aData=array(
		'sWidth'=>"450px;",
		'sHeader'=>"method=post",
		'sTitle'=>"Check Create New account",
		//'sContent'=>Base::$tpl->fetch('cart/form_check_new_account.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Create and process',
		'sSubmitAction'=>'cart_check_account',
		'sError'=>$sError,
		'sHidden'=>" <input type=hidden name=subaction value='create_new_account' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckNewAccountForm',$oForm->getForm());

		unset($aField);
		$aField['old_login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['data']['old_login'],'name'=>'data[old_login]','szir'=>1);
		$aField['old_password']=array('title'=>'Password','type'=>'password','name'=>'data[old_password]','szir'=>1);
		$aField['remember_me']=array('title'=>'Remember me','type'=>'checkbox','name'=>'remember_me','value'=>'1','class'=>'no');
		
		$aData=array(
		'sWidth'=>"420px;",
		'sHeader'=>"method=post",
		'sTitle'=>"Check Logged",
		//'sContent'=>Base::$tpl->fetch('cart/form_check_logged.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Login and process',
		'sSubmitAction'=>'cart_check_account',
		'sError'=>$sCheckLoggedError,
		'sHidden'=>" <input type=hidden name=subaction value='check_logged' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckLoggedForm',$oForm->getForm());

		Base::$sText.=Base::$tpl->fetch("cart/check_account.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step2m
	 */
	public function SelectAccount()
	{
		if (Auth::$aUser['type_']!='manager') Base::Redirect('/?action=cart_check_account');
		Base::$tpl->assign('iPathStep',2);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");
		
		$aName=Cart::GetUsersForFilter();

		$sCheckLoggedError=false;
		$sCheckNewAccountError=false;

		if (Base::$aRequest['is_post']) {
			if (Base::$aRequest['subaction']=='create_new_account'){
				$sCheckNewAccountError=$this->NewAccountManagerError();
				if (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['phone'] 
				|| $sCheckNewAccountError
				) {
					if ($sCheckNewAccountError) {
						$sError=$sCheckNewAccountError;
					} else {
						$sError="Please, fill the required fields";
					}
					Base::$tpl->assign('aUser',Base::$aRequest['data']);
				}
				else {
					$aRequestUser=String::FilterRequestData(Base::$aRequest['data'],array('login','password','email'));
					Base::$aRequest['login']=$aRequestUser['login'];
					Base::$aRequest['password']=$aRequestUser['password'];
					Base::$aRequest['email']=$aRequestUser['email'];

					$_SESSION['current_cart_package']['new_user']=User::DoNewAccount(true);

					Base::Redirect("/?action=cart_payment_method");
				}

			}
			if (Base::$aRequest['subaction']=='select_account') {
				$bOk=(Base::$aRequest['data']['old_login']>0 || Base::$aRequest['data']['old_name']>0);
				if(Base::$aRequest['data']['old_login']>0 && Base::$aRequest['data']['old_name']>0
				&& Base::$aRequest['data']['old_login']!=Base::$aRequest['data']['old_name']) $bOk=FALSE;
				if ($bOk)
				{
					if ($aOldUser) {
						//Syncronization
						//Db::Execute("update cart set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						//Db::Execute("update cart_package set id_user='".$aOldUser['id']."' where id_user='".Auth::$aUser['id']."'");
						//Db::Execute("update user set visible='0' where id='".Auth::$aUser['id']."'");

						//if(Auth::IsAuth()) Cart::RefreshCartPackage(Auth::$aUser['id']);
						Base::Redirect("/?action=cart_shipment_detail");
					}
					if(Base::$aRequest['data']['old_login']>0)$_SESSION['current_cart_package']['new_user']=Base::$aRequest['data']['old_login'];
					else $_SESSION['current_cart_package']['new_user']=Base::$aRequest['data']['old_name'];
					Cart::RecalcCartUser(Auth::$aUser['id_user'],$_SESSION['current_cart_package']['new_user']);
					Base::Redirect("/?action=cart_payment_method");
				}else
				$sCheckLoggedError="Please, enter all the fields";
			}
		}

		$_SESSION['is_checked_account']=true;
		if(!Base::$aRequest['data']['login']) $_REQUEST['data']['login']='m'.Auth::GenerateLogin();
		if(!Base::$aRequest['data']['password']) $_REQUEST['data']['password']=Auth::GeneratePassword();
		if(!Base::$aRequest['data']['verify_password']) $_REQUEST['data']['verify_password']=$_REQUEST['data']['password'];	
		
        $aField['login']=array('title'=>'Login','type'=>'input','value'=>$_REQUEST['data']['login'],'szir'=>1,'name'=>'data[login]');
        $aField['password']=array('title'=>'Password','type'=>'input','value'=>$_REQUEST['data']['password'],'name'=>'data[password]','szir'=>1);
        $aField['verify_password']=array('title'=>'Retype Password','type'=>'input','value'=>$_REQUEST['data']['verify_password'],'name'=>'data[verify_password]','szir'=>1);
        $aField['email']=array('title'=>'Email','type'=>'input','value'=>Base::$aRequest['data']['email'],'name'=>'data[email]');
        $aField['name']=array('title'=>'FLName','type'=>'input','value'=>Auth::$aUser['name']?Auth::$aUser['name']:Base::$aRequest['data']['name'],'name'=>'data[name]','szir'=>1);
        $aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Auth::$aUser['name']?Auth::$aUser['phone']:(Base::$aRequest['data']['phone']?Base::$aRequest['data']['phone']:''),'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
        $aField['city']=array('title'=>'City','type'=>'input','value'=>Auth::$aUser['name']?Auth::$aUser['city']:Base::$aRequest['data']['city'],'name'=>'data[city]');
     
		$aData=array(
		'sWidth'=>"450px;",
		'sHeader'=>"method=post",
		'sTitle'=>"Create New account",
// 		'sContent'=>Base::$tpl->fetch('cart/form_select_new_account.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Create and process',
		'sSubmitAction'=>'cart_select_account',
		'sError'=>$sError,
		'sHidden'=>" <input type=hidden name=subaction value='create_new_account' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckNewAccountForm',$oForm->getForm());
		Resource::Get()->Add('/js/select_search.js');
		
		unset($aField);
		$aData=Base::$tpl->get_template_vars('aData');
		$aField['name']=array('title'=>'Name','type'=>'select','options'=>$aName,'selected'=>$aData['old_name'],'id'=>'select_name','contexthint'=>'SelectSearchable','szir'=>1,'class'=>'select_search',
		    'name'=>'data[old_name]','onchange'=>"javascript:xajax_process_browse_url('?action=manager_customer_info_show&id='+this.options[this.selectedIndex].value);return false;");
		$aField['customer_info']=array('type'=>'span','id'=>'customer_info','colspan'=>2);
		
		$aData=array(
		'sWidth'=>"420px;",
		'sHeader'=>"method=post",
		'sTitle'=>"Select account",
		//'sContent'=>Base::$tpl->fetch('cart/form_select_account.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'process',
		'sSubmitAction'=>'cart_select_account',
		'sError'=>$sCheckLoggedError,
		'sHidden'=>" <input type=hidden name=subaction value='select_account' />",
		);
		$oForm=new Form($aData);
		Base::$tpl->assign('sCheckLoggedForm',$oForm->getForm());

		Base::$sText.=Base::$tpl->fetch("cart/check_account.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step3
	 */
	public function ShipmentDetail()
	{
		// for popup model
		Base::$aMessageJavascript = array(
		"MakeAuto_select"=> Language::GetMessage("Choose model"),
		"DetailAuto_select"=> Language::GetMessage("Choose year"),
		"add_auto_error"=>Language::GetMessage("error_add_auto"),
		"add_auto_17symbol"=> Language::GetMessage("vin_have_no_17_symbols"),
		"add_auto_model_empty"=> Language::GetMessage("model_and_series_empty"),
		"add_auto_volume_empty"=> Language::GetMessage("volume_empty"),
		);
		
		Base::$tpl->assign('iPathStep',3);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");

		Base::$tpl->assign('aUser',$aUser=Auth::$aUser);
		if (Base::$aRequest['is_post']) {
			if ( (($_SESSION['current_cart']['price_delivery'] > 0) && (!Base::$aRequest['data']['name'] 
			|| !Base::$aRequest['data']['city'] || !Base::$aRequest['data']['address'] || !Base::$aRequest['data']['phone'])
			|| (Base::$aRequest['data']['chk_order'] == 1 && Base::$aRequest['data']['chk_order'] == 0))
			 
			|| (($_SESSION['current_cart']['price_delivery'] == 0) && (!Base::$aRequest['data']['name'] 
			|| !Base::$aRequest['data']['phone']) 
			|| (Base::$aRequest['data']['chk_order'] == 1 && Base::$aRequest['data']['own_auto_id'] == 0)) 

			|| ($_SESSION['current_cart']['id_delivery_type']==6 && !Base::$aRequest['delivery_info']['np_oblast']
			&& !Base::$aRequest['delivery_info']['np_city'] && !Base::$aRequest['delivery_info']['np_sklad'])
			    
			) {
				$sError="Please, fill the required fields";
				Base::$tpl->assign('aUser',Base::$aRequest['data']);
			}
			else {
				$aRequestUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
				'name','country','city','zip','company','address','phone'
				));
				$_SESSION['current_cart']['customer_comment']=Base::$aRequest['data']['customer_comment'];
				$_SESSION['current_cart']['chk_order'] = 0;
				if (isset(Base::$aRequest['chk_order']))
					$_SESSION['current_cart']['chk_order'] = Base::$aRequest['chk_order'];
				
				$_SESSION['current_cart']['own_auto_id'] = 0;
				if (isset(Base::$aRequest['own_auto_id']))
					$_SESSION['current_cart']['own_auto_id'] = Base::$aRequest['own_auto_id']; 

				Db::Autoexecute('user_customer',$aRequestUserCustomer,'UPDATE',"id_user='".Auth::$aUser['id']."'");

				Base::Redirect("/?action=cart_payment_method");
			}
		}
		
		// check count cauto
		$iCountAuto = Db::GetOne("Select count(*) from user_auto where id_user=".Auth::$aUser['id']);
		Base::$tpl->assign('iCountAuto',$iCountAuto);
		Base::$tpl->assign('error_field_auto',$error_field_auto=Language::GetMessage('Your set check order. Please fill field auto.'));
		
		$aField['name']=array('title'=>'FLName','type'=>'input','value'=>htmlentities($aUser['name'],ENT_QUOTES,'UTF-8'),'name'=>'data[name]','szir'=>1);
		$aField['city']=array('title'=>'City','type'=>'input','value'=>htmlentities($aUser['city'],ENT_QUOTES,'UTF-8'),'name'=>'data[city]','szir'=>$_SESSION['current_cart']['price_delivery']>0);
		$aField['zip']=array('title'=>'Zip','type'=>'input','value'=>htmlentities($aUser['zip'],ENT_QUOTES,'UTF-8'),'name'=>'data[zip]');
		$aField['address']=array('title'=>'Address','type'=>'textarea','name'=>'data[address]','value'=>htmlentities($aUser['address'],ENT_QUOTES,'UTF-8'),'szir'=>$_SESSION['current_cart']['price_delivery']>0);
		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>htmlentities($aUser['phone'],ENT_QUOTES,'UTF-8'),'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
		$aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>htmlentities($aUser['remark'],ENT_QUOTES,'UTF-8'));
		
		$aField['select_your_auto']=array('title'=>'Check order','type'=>'input','value'=>Language::GetMessage('Select your auto'),'id'=>'get_own_auto','class'=>'ownautopanel','readonly'=>'readonly',
		          'onclick'=>"xajax_process_browse_url('/?action=cart_get_ownauto');$('#popup_id').show();return false;",'br'=>1,'add_to_td'=>array(
		   'own_auto_id'=>array('type'=>'hidden','name'=>'own_auto_id','value'=>'0'),
		   'own_auto_empty_txt'=>array('type'=>'hidden','name'=>'own_auto_empty_txt','value'=>Language::GetMessage('Select your auto')),
		   'chk_order_no'=>array('type'=>'radio','name'=>'chk_order','value'=>'0','caption'=>Language::GetMessage('No'),'onclick'=>"check_state();",'br'=>1),
		   'chk_order_yes'=>array('type'=>'radio','name'=>'chk_order','value'=>'1','caption'=>Language::GetMessage('Yes'),'onclick'=>"check_state();",'br'=>1)
		));
		
		$aField['error_auto']=array('type'=>'hidden','id'=>'error_auto','value'=>$error_field_auto);
		$aField['cart_shipment_submit']=array('type'=>'button','class'=>Base::$tpl->get_template_vars('sSubmitButtonClass'),'value'=>'Update and pay','onclick'=>'cart_shipment_submit(this);');
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Shipment detail form",
		//'sContent'=>Base::$tpl->fetch('cart/form_shipment_detail.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		//'sSubmitButton'=>'Update and pay',
		//'sAdditionalButtonTemplate' => 'cart/form_submit.tpl',
		'sSubmitAction'=>'cart_shipment_detail',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		$oForm->sWidth='100%';
		Base::$sText.=$oForm->GetForm();
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step4
	 */
	public function PaymentMethod()
	{
		/* hack for fixing back button on end step */
		if ($_SESSION['current_cart']['is_confirmed']) Base::Redirect('/?action=cart_package_list');

		Base::$tpl->assign('iPathStep',4);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");

		if (Base::$aRequest['is_post']) {
			$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
			Base::Redirect("/?action=cart_payment_end&data[id_payment_type]=".$aPaymentType['id']);
		}
		Cart::AssignPaymentType();
		Base::$sText.=Base::$tpl->fetch('cart/payment_method.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Step5
	 */
	public function PaymentEnd()
	{
		$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
		
		/* hack for fixing back button on end step */
		$_SESSION['current_cart']['is_confirmed']=1;
		
		//Check user info
		if(Auth::$aUser['type_']!='manager') {		    
		    if(/* !Auth::$aUser['email'] ||  */!Auth::$aUser['phone'] || !Auth::$aUser['name']) Base::Redirect("/pages/cart_check_account/");
		}

		//Base::$tpl->assign('iPathStep',5);
		//Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");

		$iUserId=(Auth::$aUser['type_']=='manager'?$_SESSION['current_cart_package']['new_user']:Auth::$aUser['id']);
		$sUserCartSql=Base::GetSql("Part/Search",array(
		"type_"=>'cart',
		"where"=> " and c.id_user='".Auth::$aUser['id']."'",
		));
		$aUserCart=Db::GetAll($sUserCartSql);

		if (!$aUserCart) Base::Redirect('?action=cart_cart&table_error=cart_not_found');
		else {
			$aUserCartId=array();
			foreach ($aUserCart as $iKey => $aValue) {
				$dPriceTotal+=Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>")*$aValue['number'];
				// field price_currency_user already sum by number and round 
				$dPriceTotalCurrencyUser=$dPriceTotalCurrencyUser+$aValue['price_currency_user'];
				
				//$dPriceTotal+=$aValue['price']*$aValue['number'];
				$aUserCartId[]=$aValue['id'];
				$aUserCart[$iKey]['print_price'] = Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>");
				$aUserCart[$iKey]['print_price_user'] = Currency::PrintPrice($aValue['price'],null,2,"<none>");
			}
		}

		$sStatus = 'new'; // old pending AT-1277
		$aCartpackageInsert=array(
		'id_user'=>$iUserId,
		'price_total'=>$dPriceTotal + $_SESSION['current_cart']['price_delivery'],
		'price_total_currency_user'=>$dPriceTotalCurrencyUser + Currency::PrintPrice($_SESSION['current_cart']['price_delivery'],null,2,"<none>"),
		'id_currency_user' => Auth::$aUser['id_currency'],
		'order_status'=> $sStatus, 
		'id_delivery_type'=>$_SESSION['current_cart']['id_delivery_type'],
		'id_payment_type'=>Base::$aRequest['data']['id_payment_type'],
		'price_delivery'=>$_SESSION['current_cart']['price_delivery'],
		'customer_comment'=>$_SESSION['current_cart']['customer_comment'],
		'is_need_check' => $_SESSION['current_cart']['is_need_check'],
		'id_own_auto' => $_SESSION['current_cart']['own_auto_id'],
		'is_web_order' => (Auth::$aUser['type_']==manager ? 0 : 1),
		'post_date' => date("Y-m-d H:i:s"),
		'post_date_changed' => date("Y-m-d H:i:s"),
	    'np_city'=> $_SESSION['current_cart']['delivery_info']['np_city'],
	    'np_sklad'=> $_SESSION['current_cart']['delivery_info']['np_sklad'],
		'np_address'=> $_SESSION['current_cart']['delivery_info']['np_address']
		);
		$aCartpackageInsert=String::FilterRequestData($aCartpackageInsert);
		Db::AutoExecute('cart_package',$aCartpackageInsert);
		$iCartPackageId=Base::$db->Insert_ID();
		// log
		Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iCartPackageId."',0,'".date("Y-m-d H:i:s")."','".$sStatus."','','".Auth::GetIp()."')");

		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>$iCartPackageId,)));

		Db::Execute("update cart set type_='order', id_cart_package='$iCartPackageId' ,order_status='pending', id_user='$iUserId'
					where id in (".implode(',',$aUserCartId).")");

		$aTextTemplate=String::GetSmartyTemplate('payment_end', array(
		'aCartPackage'=>$aCartPackage,
		'aCart'=>$aUserCart,
		));
		Base::$sText.=$aTextTemplate['parsed_text'];
		$aData=array(
				'table'=>'payment_type',
				'where'=>" and id=".Base::$aRequest['data']['id_payment_type'],
		);
		$aPaymentType=Language::GetLocalizedRow($aData);
		Base::$tpl->assign('aPaymentType',$aPaymentType);
		/*$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
		Base::$tpl->assign('aPaymentType',$aPaymentType);*/
		Base::$sText.=$aPaymentType['end_description'];

		// send letter if new user
		if (Auth::$aUser['type_']=='manager') {
			$aUser = Db::GetRow("select * from user where id='".$_SESSION['current_cart_package']['new_user']."'");
			if ($aUser['email'] && $aUser['password_temp']) {		
				$aManager=Db::GetRow("SELECT um.*, u2.login
					FROM user u
					INNER JOIN user_customer uc ON u.id = uc.id_user
					INNER JOIN user_manager um ON uc.id_manager = um.id_user
					INNER JOIN user u2 ON u2.id = uc.id_manager
					WHERE u.id ='".$aUser['id']."'");
				$sLink="<A href='http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature']."'
								>".Base::$language->getMessage('Confirm')."</a>";
				$sUrl="http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$aUser['signature'];		
				$aData=array(
						'info'=>array(
								'link'=>$sLink,
								'url'=>$sUrl,
								'login'=>$aUser['login'],
								'password'=>$aUser['password_temp'],
								'email'=>$aUser['email'],
						),
						'aManager'=>$aManager
				);
				$aSmartyTemplate=String::GetSmartyTemplate('confirmation_letter', $aData);
				$sBody=$aSmartyTemplate['parsed_text'];
				
				Mail::AddDelayed($aUser['email'],Base::$language->getMessage('Confirmation Letter'),$sBody,'','',true,2);
			}
		}
		// confirmation-end	
		
		$aSmartyTemplate=String::GetSmartyTemplate('cart_package_details', array(
		'aCartPackage'=>$aCartPackage,
		'aCart'=>$aUserCart,
		));
		
		$sUserrEmail=Auth::$aUser['type_']=='manager'?Db::GetOne("select email from user where id='".$_SESSION['current_cart_package']['new_user']."'"):Auth::$aUser['email'];
		Mail::AddDelayed($sUserrEmail
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
			$this->SendPendingWork($aCartPackage['id']);
		}
		
		$_SESSION['current_cart_package']['price_total'] = Currency::PrintPrice($aCartPackage['price_total'],0,2,'<none>');

		Base::$oContent->AddCrumb('Заказ оформлен');
		Base::$sText.=Base::$tpl->fetch("cart/payment_end_button.tpl");
		
		if($aCartPackage['id_payment_type']==3) {
		    Base::$sText.= Payment::LiqPayGetForm($aCartPackage['id'],$aCartPackage['price_total']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Methos for payment for cart package
	 */
	public function PaymentEndButton()
	{
		Base::$tpl->assign('iPathStep',5);
		Base::$sText.=Base::$tpl->fetch("cart/path_cart_package.tpl");

		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array(
		'id'=>(Base::$aRequest['id_cart_package']? Base::$aRequest['id_cart_package']:-1),
		)));

		$aTextTemplate=String::GetSmartyTemplate('payment_end', array(
		'aCartPackage'=>$aCartPackage,
		));
		Base::$sText.=$aTextTemplate['parsed_text'];

		$aData=array(
				'table'=>'payment_type',
				'where'=>" and id=".Base::$aRequest['data']['id_payment_type'],
		);
		$aPaymentType=Language::GetLocalizedRow($aData);
		Base::$tpl->assign('aPaymentType',$aPaymentType);
		/*$aPaymentType=Db::GetRow(Base::GetSql('PaymentType',array('id'=>Base::$aRequest['data']['id_payment_type'])));
		Base::$tpl->assign('aPaymentType',$aPaymentType);*/
		Base::$sText.=$aPaymentType['end_description'];

		$_SESSION['current_cart_package']['price_total'] = Currency::PrintPrice($aCartPackage['price_total'],0,2,'<none>');

		Base::$sText.=Base::$tpl->fetch("cart/payment_end_button.tpl");
	}
	//-----------------------------------------------------------------------------------------------

	/**
	 * Main method to send carts to orders and withdraw money for that
	 *
	 * @param $iIdCartPackage
	 * @return unknown
	 */
	public function SendPendingWork($iIdCartPackage)
	{
		$aCartPackage=Db::GetRow( Base::GetSql('CartPackage',array('id'=>$iIdCartPackage)));
		$aUserCart=Db::GetAll("select * from cart
			where id_cart_package='$iIdCartPackage'
				and order_status='pending'
				and type_='order'");
		$aCustomer=Db::GetRow( Base::GetSql('Customer',array('id'=>$aCartPackage['id_user'])) );

		if (!$aCartPackage || !$aUserCart) return false;

		$aUserCartId=array();
		$aFullPaymentCart=array();
		foreach ($aUserCart as $aValue) {
			$dPriceTotal+=Currency::PrintPrice($aValue['price'],null,0,'<none>')*$aValue['number'];
			$aUserCartId[]=$aValue['id'];
		}

		//		if (Finance::HaveMoney($aCartPackage['price_total'],$aCartPackage['id_user']) &&
		//			$aCartPackage['order_status']=="pending") {
		//			Finance::Deposit($aCartPackage['id_user'],-$aCartPackage['price_total'],
		//				Language::getMessage("Autopayment order #")." $aCartPackage[id]",$aCartPackage[id],
		//				'internal','internal','',9);
		//			Db::Execute("update cart set order_status='new',post_date=now()	where id in (".implode(',',$aUserCartId).")");
		//			Db::Execute("update cart_package set order_status='work',is_payed=1 where id='$iIdCartPackage'");
		//		}
		
		$aOperation = Db::GetRow("Select * from user_account_type_operation where code='pending_work'");
		Finance::Deposit($aCartPackage['id_user'],$dPriceTotal,$aOperation['name'],$iIdCartPackage,'interval','',0,0,0,$aOperation['code'],0,0,true,0);
		
		$aOperation = Db::GetRow("Select * from user_account_type_operation where code='pay_delivery'");
		Finance::Deposit($aCartPackage['id_user'],$aCartPackage['price_delivery'],$aOperation['name'],$iIdCartPackage,'interval','',0,0,0,$aOperation['code'],0,0,true,0);
		
		Db::Execute("update cart_package set order_status='work',is_viewed='1',post_date_changed='".date("Y-m-d H:i:s")."' where id='$iIdCartPackage'");
		$iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
		// log
		Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    values ('".$iIdCartPackage."','".$iIdManager."','".date("Y-m-d H:i:s")."','work','','".Auth::GetIp()."')");

		Db::Execute("update cart set order_status='new',post_date=now()	where id in (".implode(',',$aUserCartId).")");
	}
	//-----------------------------------------------------------------------------------------------
	public function PackagePrint()
	{
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array(
		'where'=>"and cp.id='".Base::$aRequest['id']."' and cp.id_user='".Auth::$aUser['id']."'")));
		$aUserCart=Db::GetAll(Base::GetSql("Part/Search",array(
		"where"=>" and c.id_cart_package='".Base::$aRequest['id']."' and c.type_='order' and c.id_user='".Auth::$aUser['id']."'",
		)));
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
		'id'=>($aCartPackage['id_user']? $aCartPackage['id_user']:-1),
		)));
		if (!$aUserCart || !$aCartPackage) Base::Redirect('?action=cart_package&table_error=cart_package_not_found');
		$aAccount=Db::GetRow(Base::GetSql('Account',array('where'=>" and is_active=1")));
		if (!$aAccount) Base::Redirect('?action=cart_package_list&table_error=cart_package_list_no_active_account');
		$aCartPackage['price_total_string']=Currency::CurrecyConvert(Currency::PrintPrice($aCartPackage['price_total'],1,2,'<none>'),
		Base::GetConstant('global:base_currency'));
		$aCartPackage['nds']=round((Currency::PrintPrice($aCartPackage['price_total'],1,2,'<none>')/118*18),2);

		Base::$tpl->assign('aUserCart',$aUserCart);
		Base::$tpl->assign('aCartPackage',$aCartPackage);
		Base::$tpl->assign('aCustomer',$aCustomer);
		Base::$tpl->assign('aAccount',$aAccount);
		Base::$tpl->assign('aActiveAccount',$aAccount);

		PrintContent::Append(Base::$tpl->fetch('cart/package_print.tpl'));
		Base::Redirect('?action=print_content&return=cart_package_list');

	}
	//-----------------------------------------------------------------------------------------------
	public function PackageList()
	{
		Base::$aTopPageTemplate=array('panel/tab_customer_cart.tpl'=>'cart_package');

		//--------------------------------------------------------------------------
		if (Base::$aRequest['is_post'] && Base::$aRequest['action']!='cart_package_order') {
			//[----- UPDATE -----------------------------------------------------]
			$aCartPackageUpdate=String::FilterRequestData(Base::$aRequest['data'],array('id_payment_type','customer_comment'));
			Db::AutoExecute('cart_package',$aCartPackageUpdate,'UPDATE'," id='".Base::$aRequest['id']."' ".Auth::$sWhere);

			Base::Redirect("/?action=cart_package_list");
		}


		if (Base::$aRequest['action']=='cart_package_edit') {
			if (Base::$aRequest['action']=='cart_package_edit') {
				$aCartPackage=Db::GetRow("select * from cart_package as cp
				    where cp.id='".Base::$aRequest['id']."'
							".Auth::$sWhere);
				Base::$tpl->assign('aData',$aCartPackage);
			}
			Base::$tpl->assign('aPaymentType',$aPaymentType=Db::GetAssoc('Assoc/PaymentType',array('where'=>" and pt.visible=1")));
// 			$aPaymentType=Cart::AssignPaymentType(true);

			$aField['id_payment_type']=array('title'=>'Payment type','type'=>'select','options'=>$aPaymentType,'selected'=>$aCartPackage['id_payment_type'],'name'=>'data[id_payment_type]');
			$aField['customer_comment']=array('title'=>'Customer comment','type'=>'textarea','name'=>'data[customer_comment]','value'=>$aCartPackage['customer_comment']);

			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cart Package",
			//'sContent'=>Base::$tpl->fetch('cart/form_cart_package.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'cart_package_list',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'cart_package_list',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();

			return;
		}
		
		$aOrderStatus=array(
		    ''=>Language::GetMessage('All'),
		    'work'=>Language::GetMessage('work'),
		    'pending'=>Language::GetMessage('pending'),
		    'end'=>Language::GetMessage('end'),
		    'refused'=>Language::GetMessage('refused'),
		);
		
		$aField['id']=array('title'=>'#','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['number_declaration']=array('title'=>'Number declaration','type'=>'input','value'=>Base::$aRequest['search']['number_declaration'],'name'=>'search[number_declaration]');
		$aField['order_status']=array('title'=>'Status','type'=>'select','options'=>$aOrderStatus,'name'=>'search_order_status','selected'=>Base::$aRequest['search_order_status']);
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()-30*86400),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()+86400),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aData=array(
		    'sHeader'=>"method=get",
// 		    'sContent'=>Base::$tpl->fetch('cart/form_cart_package_search.tpl'),
            'aField'=>$aField,
	        'bType'=>'generate',
		    'sGenerateTpl'=>'form/index_search.tpl',
		    'sSubmitButton'=>'Search',
		    'sSubmitAction'=>'cart_package_list',
		    'sReturnButton'=>'Clear',
		    'sReturnAction'=>'cart_package_list',
		    'sWidth'=> '28%',
		    'sError'=>$sError,
		    'bIsPost'=>false,
		);
		$oForm=new Form($aData);
		
		Base::$sText.=$oForm->getForm();
		
		
		// --- search ---
		if (Base::$aRequest['search']['id'])
		    $sWhere.="  and cp.id like '%".Base::$aRequest['search']['id']."%' ";
		if (Base::$aRequest['search_order_status']) 
		    $sWhere.=" and cp.order_status ='".Base::$aRequest['search_order_status']."'";
		if (Base::$aRequest['search']['number_declaration']) {
		    $sWhere.=" and pd.number_declaration like '%".Base::$aRequest['search']['number_declaration']."%'";
		}
		if (Base::$aRequest['search']['date']) {
		    $sWhere.=" and (cp.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and cp.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'])."') ";
		}
		// --- search ---
		
		//FLB-28
		$aCartPackage=Db::GetAll(Base::GetSql('CartPackage',array(
		'where'=>" and cp.is_archive='0' and cp.id_user='".Auth::$aUser['id']."'".$sWhere,
		)));
		
		
// 		if((Auth::$aUser['id_customer_group']==1 || Auth::$aUser['id_customer_group']==30) ) {
	    $liqpay = new LiqPay(Base::GetConstant('liqpay:public_key','i48066539671'), Base::GetConstant('liqpay:private_key','plKvI40SGyr8Opr8FxSucf6M8SG5DwiAqpUXVDkQ'));
	    
		    foreach ($aCartPackage as $key => $value) {
		        if($value['id_payment_type']=='3' && $value['is_payed']=='0'){
		            $res = $liqpay->api("request", array(
		                'action'        => 'status',
		                'version'       => '3',
		                'order_id'      => 'order_id_'.$value['id']
		            ));
		            $res=json_encode($res);
		            $res=json_decode($res, true);
		            $aCartPackage[$key]['status_liq']=$res['status'];
		            
		            if ($aCartPackage[$key]['status_liq']=='success'){
		                Db::Execute("update cart_package as cp set cp.is_payed='1' where cp.id=".$value['id']."");
		            }else{
		                $aCartPackage[$key]['html'] = Payment::LiqPayGetFormOrder($value['id'],$value['price_total']);
		            } 
		            
		            
		        }
		    }
// 		}
		//end
		
		$oTable=new Table();
		$oTable->sType                 = 'array';
		$oTable->aDataFoTable          = $aCartPackage;
// 		$oTable->sSql=Base::GetSql('CartPackage',array(
// 		'where'=>" and cp.is_archive='0' and cp.id_user='".Auth::$aUser['id']."'".$sWhere,
// 		));
		$oTable->aOrdered="order by cp.post_date desc";
		$oTable->aColumn=array(
// 		'id'=>array('sTitle'=>'ID'),
// 		'order_status'=>array('sTitle'=>'Order Status'),
// 		'price_total'=>array('sTitle'=>'Total'),
// 		'customer_comment'=>array('sTitle'=>'Comment'),
// 		'number_declaration'=>array('sTitle'=>'Number declaration'),
// 		'post'=>array('sTitle'=>'Date'),
// 		'action'=>array(),
		);
		$oTable->sDataTemplate='cart/row_cart_package.tpl';
// 		$oTable->sButtonTemplate='cart/button_cart_package.tpl';
		$oTable->bCheckVisible=false;
		//$oTable->aCallback=array($this,'CallParseCartPackage');

		Base::$sText.=$oTable->getTable("Cart Packages",'cart_package_table');
	}
	//-----------------------------------------------------------------------------------------------
	public function NewAccountError()
	{
		if (!preg_match('/^[a-zA-Z0-9_]+$/',Base::$aRequest['data']['login']))
		return "Login must contain only latin letters and numbers";

		if (!Base::$aRequest['data']['user_agreement'])
		return "You need to apply user agreemnt";

		if (!Base::$aRequest['data']['login']||!Base::$aRequest['data']['password'])
		return "Please, enter all the fields";

		if (Base::$aRequest['data']['password']!=Base::$aRequest['data']['verify_password'])
		return "Passwosds are different. Please try again";

		if (Base::$aRequest['data']['password']==Base::$aRequest['data']['login'])
		return "Login and password must be different. Please try again";

		if (strlen(Base::$aRequest['data']['password'])<4)
		return "Password can't be less then 4 digits";

		if(Base::$aRequest['data']['email']){
    		if (!String::CheckEmail(Base::$aRequest['data']['email']))
    		return "Please, check your email";
    		
    		$sQuery="select * from user where email='".Base::$aRequest['data']['email']."'";
    		$aUser=Db::GetRow($sQuery);
    		if ($aUser)	return "This email is already registered. Please use the link \"Forgot password\".";
		}
		$sQuery="select * from user where login='".Base::$aRequest['data']['login']."'";
		$aUser=Db::GetRow($sQuery);
		if ($aUser)	return "This login is already occupied. Please choose different one.";

		return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function NewAccountManagerError()
	{
		if (!preg_match('/^[a-zA-Z0-9_]+$/',Base::$aRequest['data']['login']))
		return "Login must contain only latin letters and numbers";

		if (!Base::$aRequest['data']['login']||!Base::$aRequest['data']['password']||!Base::$aRequest['data']['name']||!Base::$aRequest['data']['phone'])
		return "Please, enter all the fields";

		if (Base::$aRequest['data']['password']!=Base::$aRequest['data']['verify_password'])
		return "Passwosds are different. Please try again";

		if (Base::$aRequest['data']['password']==Base::$aRequest['data']['login'])
		return "Login and password must be different. Please try again";

		if (strlen(Base::$aRequest['data']['password'])<4)
		return "Password can't be less then 4 digits";

		$sQuery="select * from user where login='".Base::$aRequest['data']['login']."'";
		$aUser=Db::GetRow($sQuery);
		if ($aUser)	return "This login is already occupied. Please choose different one.";

		return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function PopUpGetOwnAuto() {
		Base::$oResponse->AddAssign('popup_caption_id','innerHTML', Language::GetMessage('Select your auto'));
		
		$oTable=new Table();
		$oTable->iRowPerPage=500;
		$oTable->sSql=Base::GetSql('UserAuto',array());
		$oTable->aOrdered="order by ua.id desc";
		$oTable->aColumn=array(
				'id_make'			=> array('sTitle'=>Language::GetMessage('Make auto')),
				'id_model'			=> array('sTitle'=>Language::GetMessage('Model auto')),
				'year'				=> array('sTitle'=>Language::GetMessage('Year')),
		);
		$oObject = new OwnAuto();
		$oTable->sDataTemplate='cart/row_user_auto.tpl';
		$oTable->aCallback=array($oObject,'CallParseUserAuto');
		$oTable->sTemplateName = 'cart/table_popup.tpl';
		$sText = $oTable->getTable("User auto");
		
		// add auto hidden form
		$oObject = new OwnAuto();
		$sText .= '<div id="add_form_auto" style="display:none;">' . $oObject->GetFormAddAuto($oObject, "Add auto", 0, array()) ."</div>";
		
		Base::$tpl->assign('sContent',$sText);
		Base::$oResponse->AddAssign('popup_content_id','innerHTML',Base::$tpl->fetch('cart/message.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	public function RecalcCartUser($iIdUser,$iIdUserNew,$iIdCartPackage=0)
	{
			if (!$iIdUser || !$iIdUserNew || $iIdUserNew==$iIdUser) return;
			$aCart=Db::GetAll("select * from cart where id_user='".$iIdUser."' and id_cart_package='".$iIdCartPackage."'");
			$aUser=Db::GetRow(Base::GetSql("Customer",array('id'=>$iIdUserNew)));
			if(Auth::$aUser['type_']=='manager')
			    $iIdUserNotManager=1;
			else $iIdUserNotManager=0;
			
			if($aCart){
					foreach ($aCart as $aValue) {
						$a=Db::GetRow(Base::GetSql('Catalog/Price',array(
						'id_provider'=>$aValue['id_provider']
						, 'aItemCode'=>array($aValue['item_code'])
						, 'id_part'=>$aValue['id_part']
						, 'customer_discount'=>Discount::CustomerDiscount($aUser)
						    , 'not_change_recalc'=> $iIdUserNotManager
						)));
						if($a!=array()&&$a['price']!=$aValue['price'])
						Db::Execute("update cart set price='".Currency::GetPriceWithoutSymbol($a['price'])."' where id='".$aValue['id']."'");
					}
			}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetBoardExpiredCartUser($iIdUser) {
		$aUser = Db::GetRow(Base::GetSql("Customer",array('id'=>$iIdUser)));
		if ($aUser['hours_expired_cart'])
			return $aUser['hours_expired_cart'];
		return 0;
	}
	//-----------------------------------------------------------------------------------------------
	public function CartExpiredCountPositions($iUserId = 0) {
		if ($iUserId == 0)
			$iUserId = Auth::$aUser['id'];

		if ($iUserId == 0)
			return 0;
		
		return Db::GetOne("select count(*) from cart_deleted where id_user = ".$iUserId);
	}
	//-----------------------------------------------------------------------------------------------
	public function CartExpiredInfo($iUserId = 0) {
		Base::$oContent->AddCrumb(Language::GetMessage('CartExpiredInfo'),'');
		if ($iUserId == 0)
			$iUserId = Auth::$aUser['id'];
		
		if ($iUserId == 0) {
			Base::Redirect('/pages/cart_cart');
		}
		
		$oTable=new Table();
		$sWhere.=" and c.id_user=".$iUserId;
		$oTable->sSql=Base::GetSql("Part/SearchExpired",array(
				"type_"=>'cart',
				"where"=>$sWhere,
		));
		
		$oTable->aOrdered="order by c.post_delete desc";
		$oTable->iRowPerPage=30;
		$oTable->aColumn=array(
				'post_delete'=>array('sTitle'=>'Date delete'),
				'brand'=>array('sTitle'=>'Brand'),
				'code'=>array('sTitle'=>'CartCode'),
		);
		$oTable->sDataTemplate='cart/row_cart_expired.tpl';
		$oTable->sButtonTemplate='cart/button_cart_expired.tpl';
		$oTable->bCheckVisible=false;
		$oTable->bStepperVisible=false;
		
		Base::$sText.=$oTable->getTable("Cart Items Deleted");
		Base::$tpl->assign('aData',$aData);
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderByPhone(){
		if(Base::$aRequest['phone']) {
		    Base::$aRequest['phone']=String::FormatPhoneNumber(Base::$aRequest['phone']);
			$aCartList=Db::GetAll(Base::GetSql("Part/Search",array(
				"type_"=>'cart',
				"where"=>" and c.id_user=".Auth::$aUser['id'],
			)));
			
			if (!$aCartList) Base::Redirect('/?action=cart_cart&table_error=cart_not_found');
			else {
				$aSmartyTemplate=String::GetSmartyTemplate('cart_order_by_phone', array(
					'sPhone'=>Base::$aRequest['phone'],
					'aCart'=>$aCartList,
				));
				
				if(!Customer::IsTempUser(Auth::$aUser['login'])) {
					//normal user
					$iUserId=Auth::$aUser['id'];
					
					if(Auth::$aUser['type_']=='manager') {
						$is_web_order = 0;
						//manager
					    $iUserId=Db::GetOne("select id from user where login='".Base::$aRequest['phone']."' ");
    				    if(!$iUserId) {
    				        Base::$aRequest['data']['phone']=Base::$aRequest['phone'];
    				        $aOrderUser=Cart::AutoCreateUser(Base::$aRequest['phone']);
    				        $iUserId=$aOrderUser['id'];
    				    }
    				    else {
							// check user is manager
							$isCustomer = Db::getOne("Select type_ from user where id=".$iUserId." and type_='customer'");
							if (!$isCustomer)
								Base::Redirect('/?action=cart_cart&table_error=phone_is_manager');
    				    }
    				    
						// recalc cart
						User::RecalcCart($iUserId,1);
						// reload cart with new prices
						$aCartList=Db::GetAll(Base::GetSql("Part/Search",array(
								"type_"=>'cart',
								"where"=>" and c.id_user=".Auth::$aUser['id'],
						)));
						
					} else {
						$is_web_order = 1;
						//update user phone
						Db::Execute("update user_customer set phone='".Base::$aRequest['phone']."' where id_user='".$iUserId."' ");
					}
				} else {
				    //check login
				    $iUserId=Db::GetOne("select id from user where login='".Base::$aRequest['phone']."' ");
				    if(!$iUserId) {
    					//guest
    					Base::$aRequest['data']['phone']=Base::$aRequest['phone'];
    					$aOrderUser=Cart::AutoCreateUser(Base::$aRequest['phone']);
    					$iUserId=$aOrderUser['id'];
				    }
				}
				
				$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
				$dPriceTotal=0;
				$aUserCartId=array();
				foreach ($aCartList as $aValue) {
					$dPriceTotal+=Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>")*$aValue['number'];
					$aUserCartId[]=$aValue['id'];
				}
				
				$sStatus = 'new'; // old pending - AT-1277
				$aCartpackageInsert=array(
					'id_user'=>$iUserId,
					'price_total'=>$dPriceTotal + 0,
					'order_status'=>$sStatus,
					'id_delivery_type'=>1,
					'id_payment_type'=>2,
					'price_delivery'=>0,
					'customer_comment'=>'',
					'is_need_check' => 0,
					'id_own_auto' => 0,
					'is_web_order' => $is_web_order,
					'post_date' => date("Y-m-d H:i:s"),
					'post_date_changed' => date("Y-m-d H:i:s"),
				);
				$aCartpackageInsert=String::FilterRequestData($aCartpackageInsert);
				Db::AutoExecute('cart_package',$aCartpackageInsert);
				$iCartPackageId=Base::$db->Insert_ID();
				// log
				$iIdManager = (Auth::$aUser['type_']=='manager' ? Auth::$aUser['id_user'] : 0);
				Base::$db->Execute("insert into cart_package_log (id_cart_package,id_user_manager,post_date,order_status,comment,ip)
		    		values ('".$iCartPackageId."','".$iIdManager."','".date("Y-m-d H:i:s")."','".$sStatus."','','".Auth::GetIp()."')");
				
				$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>$iCartPackageId,)));
				
				Db::Execute("update user set is_temp=0 where id='".$iUserId."' ");
				
				Db::Execute("update cart set type_='order', id_cart_package='$iCartPackageId' ,order_status='pending', id_user='$iUserId'
				where id in (".implode(',',$aUserCartId).")");
				
				Mail::AddDelayed(Base::GetConstant('order_by_phone_email','info@mstarproject.com'),
				    $aSmartyTemplate['name'].":".$aCartPackage['id']." - ".Base::$aRequest['phone'],$aSmartyTemplate['parsed_text'],'','',false);
								
				if ($aCartPackage['id'] && Finance::HaveMoney($aCartPackage['price_total'],$aCartPackage['id_user'])) {
					$this->SendPendingWork($aCartPackage['id']);
				}
				
				Base::$oContent->AddCrumb('Заказ по телефону');
				Base::$sText.=Language::GetText("order_by_phone_success");
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function NewAccountDeliveryInfoFields(){
	    $aEntityType=Base::$tpl->get_template_vars('aEntityType');
	    $aUserCustomerType=Base::$tpl->get_template_vars('aUserCustomerType');
	    foreach ($aEntityType as $aValue){
	        $aEntityTypeOptions[$aValue]=$aValue;
	    }
	    $aField['id_user_customer_type']=array('title'=>'User customer type','type'=>'select','options'=>$aUserCustomerType,'selected'=>Base::$aRequest['data']['id_user_customer_type']?Base::$aRequest['data']['id_user_customer_type']:Auth::$aUser['id_user_customer_type'],
	        'name'=>'data[id_user_customer_type]','onchange'=>"oUser.ToggleEntityTr($('#user_customer_type_id').val())",'id'=>'user_customer_type_id');
	    $aField['entity_type']=array('title'=>'Entity name','type'=>'select','options'=>$aEntityTypeOptions,'selected'=>(Auth::$aUser['entity_type']!='')?Auth::$aUser['entity_type']:Base::$aRequest['data']['entity_type'],'name'=>'data[entity_type]','tr_id'=>'entity_tr_id','add_to_td'=>array(
	        'entity_name'=>array('type'=>'input','value'=>Auth::$aUser['entity_name']?Auth::$aUser['entity_name']:Base::$aRequest['data']['entity_name'],'name'=>'data[entity_name]','tr_id'=>'entity_tr_id')
	    ));
	    $aField['additional_field1']=array('title'=>'additional_field1','type'=>'input','value'=>Auth::$aUser['additional_field1']?Auth::$aUser['additional_field1']:Base::$aRequest['data']['additional_field1'],'name'=>'data[additional_field1]','tr_id'=>'additional_field1_tr_id');
	    $aField['additional_field2']=array('title'=>'additional_field2','type'=>'input','value'=>Auth::$aUser['additional_field2']?Auth::$aUser['additional_field2']:Base::$aRequest['data']['additional_field2'],'name'=>'data[additional_field2]','tr_id'=>'additional_field2_tr_id');
	    $aField['additional_field3']=array('title'=>'additional_field3','type'=>'input','value'=>Auth::$aUser['additional_field3']?Auth::$aUser['additional_field3']:Base::$aRequest['data']['additional_field3'],'name'=>'data[additional_field3]','tr_id'=>'additional_field3_tr_id');
	    $aField['additional_field4']=array('title'=>'additional_field4','type'=>'input','value'=>Auth::$aUser['additional_field4']?Auth::$aUser['additional_field4']:Base::$aRequest['data']['additional_field4'],'name'=>'data[additional_field4]','tr_id'=>'additional_field4_tr_id');
	    $aField['additional_field5']=array('title'=>'additional_field5','type'=>'input','value'=>Auth::$aUser['additional_field5']?Auth::$aUser['additional_field5']:Base::$aRequest['data']['additional_field5'],'name'=>'data[additional_field5]','tr_id'=>'additional_field5_tr_id');
	    $aField['name']=array('title'=>'FLName','type'=>'input','value'=>Base::$aRequest['data']['name']?Base::$aRequest['data']['name']:Auth::$aUser['name'],'name'=>'data[name]','szir'=>1);
	    $aField['city']=array('title'=>'City','type'=>'input','value'=>Base::$aRequest['data']['city']?Base::$aRequest['data']['city']:Auth::$aUser['city'],'name'=>'data[city]','szir'=>1);
	    $aField['address']=array('title'=>'Address','type'=>'input','value'=>Base::$aRequest['data']['address']?Base::$aRequest['data']['address']:Auth::$aUser['address'],'name'=>'data[address]','szir'=>1);
	    $aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Auth::$aUser['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
	    $aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>Base::$aRequest['data']['remark']?Base::$aRequest['data']['remark']:Auth::$aUser['remark']);
	    if(Auth::$aUser['id_user_customer_type']!=''){
	        if(Base::$aRequest['data']['id_user_customer_type']==1)
	        {
	            $aField['entity_type']['tr_style']="display:none;";
	            $aField['entity_name']['tr_style']="display:none;";
	            $aField['additional_field1']['tr_style']="display:none;";
	            $aField['additional_field2']['tr_style']="display:none;";
	            $aField['additional_field3']['tr_style']="display:none;";
	            $aField['additional_field4']['tr_style']="display:none;";
	            $aField['additional_field5']['tr_style']="display:none;";
	        }
	    } else {
	        if(Base::$aRequest['data']['id_user_customer_type']==1 || !Base::$aRequest['data']['id_user_customer_type'])
	        {
	            $aField['entity_type']['tr_style']="display:none;";
	            $aField['entity_name']['tr_style']="display:none;";
	            $aField['additional_field1']['tr_style']="display:none;";
	            $aField['additional_field2']['tr_style']="display:none;";
	            $aField['additional_field3']['tr_style']="display:none;";
	            $aField['additional_field4']['tr_style']="display:none;";
	            $aField['additional_field5']['tr_style']="display:none;";
	        }
	    }
	    return $aField;
	}
	//-----------------------------------------------------------------------------------------------
	public function CartOnepageDeliveryFields(){
	    $aDeliveryType=Base::$tpl->get_template_vars('aDeliveryType');
	    foreach ($aDeliveryType as $aItem){
            if(!$bAlreadySelectedDelivery){
	            $bAlreadySelectedDelivery=1;
	            $aField['delivery_type'.$aItem['id']]=array('title'=>'Delivery methods','type'=>'radio','name'=>'id_delivery_type','class'=>'bg-radio','value'=>$aItem['id'],'checked'=>1,
	                'onclick'=>"show_delivery_description('delivery_description_".$aItem['id']."');xajax_process_browse_url('?action=delivery_set&xajax_request=1&id_delivery_type=".$aItem['id']."');",'caption'=>$aItem['name'],'br'=>1,'add_to_td'=>array());
	            $NameOfFirstField='delivery_type'.$aItem['id'];
	        } else {
	            $aField[$NameOfFirstField]['add_to_td']=array_merge($aField[$NameOfFirstField]['add_to_td'],array('delivery_type'.$aItem['id']=>array('type'=>'radio','name'=>'id_delivery_type','class'=>'bg-radio','value'=>$aItem['id'],
	                'onclick'=>"show_delivery_description('delivery_description_".$aItem['id']."');xajax_process_browse_url('?action=delivery_set&xajax_request=1&id_delivery_type=".$aItem['id']."');",'caption'=>$aItem['name'],'br'=>1)));
	        }
	    }
	    $aField['hr1']=array('colspan'=>2,'type'=>'hr');
	    foreach ($aDeliveryType as $aItem){
	        if (!$bAlreadySelected3){
	           $bAlreadySelected3=1;
	           $aField['delivery_description_'.$aItem['id']]=array('type'=>'span','class'=>'delivery_description delivery_description_'.$aItem['id'],'value'=>$aItem['description'],'style'=>"display:block;",'colspan'=>2,'add_to_td'=>array());
	           $NameOfFirstField='delivery_description_'.$aItem['id'];
	        }else
	           $aField[$NameOfFirstField]['add_to_td']=array_merge($aField[$NameOfFirstField]['add_to_td'],array('delivery_description_'.$aItem['id']=>
	               array('type'=>'span','class'=>'delivery_description delivery_description_'.$aItem['id'],'value'=>$aItem['description'],'style'=>"display:none;")));
	     }
	     $aField['hr2']=array('colspan'=>2,'type'=>'hr');
	     return $aField;
	}
	//-----------------------------------------------------------------------------------------------
	public function CartOnepagePaymentFields(){
	    $aPaymentType=Base::$tpl->get_template_vars('aPaymentType');
	    foreach ($aPaymentType as $aItem){
            if (!$bAlreadySelected){
	            $bAlreadySelected=1;
	            $aField['payment_type'.$aItem['id']]=array('title'=>'Payment methods','type'=>'radio','name'=>'data[id_payment_type]','value'=>$aItem['id'],'onclick'=>"show_payment_description('payment_description_".$aItem['id']."')",
	                'caption'=>$aItem['url']?$aItem['name']."&nbsp<a href='".$aItem['url']."' target=_blank>".$aItem['url']."</a>":$aItem['name'],'checked'=>1,'br'=>1,'add_to_td'=>array());
	            $NameOfFirstField='payment_type'.$aItem['id'];
	        } else 
	            $aField[$NameOfFirstField]['add_to_td']=array_merge($aField[$NameOfFirstField]['add_to_td'],array('payment_type'.$aItem['id']=>
	                array('type'=>'radio','name'=>'data[id_payment_type]','value'=>$aItem['id'],'onclick'=>"show_payment_description('payment_description_".$aItem['id']."')",
	                   'caption'=>$aItem['url']?$aItem['name']."&nbsp<a href='".$aItem['url']."' target=_blank>".$aItem['url']."</a>":$aItem['name'],'br'=>1)));
	    }
	    $aField['hr3']=array('colspan'=>2,'type'=>'hr');
	    foreach ($aPaymentType as $aItem){
	        if (!$bAlreadySelected2){
	            $bAlreadySelected2=1;
	            $aField['payment_description_'.$aItem['id']]=array('type'=>'span','class'=>'payment_description payment_description_'.$aItem['id'],'value'=>$aItem['description'],'style'=>"display:block;",'colspan'=>2,'add_to_td'=>array());
	            $NameOfFirstField='payment_description_'.$aItem['id'];
	        }else
	            $aField[$NameOfFirstField]['add_to_td']=array_merge($aField[$NameOfFirstField]['add_to_td'],array('payment_description_'.$aItem['id']=>
	                array('type'=>'span','class'=>'payment_description payment_description_'.$aItem['id'],'value'=>$aItem['description'],'style'=>"display:none;")));
	    }
	    return $aField;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetUsersForFilter() {
	    if(Auth::$aUser['is_super_manager'])
	        $sWhereManager = ' ';
	    else
	        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    
	    $aName=array(-1=>'')+Db::GetAssoc("select 
	        id as id, 
	        concat(ifnull(uc.name,''),' ( ',u.login,' )', IF(uc.phone is null or uc.phone='','',concat(' ". Language::getMessage('tel.')." ',uc.phone)),' [', Replace(Replace(Replace(u.login,'(',''),')',''),'-','') ,']') name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where 
	        u.visible=1 
	        /*and uc.name is not null and trim(uc.name)!=''*/
		    ".$sWhereManager."
		order by uc.name");
	    
	    Base::$tpl->assign('aName', $aName);
	    return $aName;
	}
	//-----------------------------------------------------------------------------------------------
	public function AutoCreateUser($sLogin)
	{
	    if (!$sLogin) $sLogin=Auth::GenerateLogin();
	    $bCheckedLogin=false;
	    if (Auth::CheckLogin($sLogin)) $bCheckedLogin=true;
	    if (!$bCheckedLogin) for ($i=0;$i<=100;$i++) {
	        $sLogin=Auth::GenerateLogin();
	        if (Auth::CheckLogin($sLogin)) {
	            $bCheckedLogin=true;
	            break;
	        }
	    }
	    if ($bCheckedLogin) {
	        $oUser= new User();
	        Base::$aRequest['login']=$sLogin;
	        Base::$aRequest['password']=Auth::GeneratePassword();
	        if (Base::$aRequest['mobile']) {
	            Base::$aRequest['phone']=Base::$aRequest['operator'].Base::$aRequest['mobile'];
	            Base::$aRequest['data']['phone']=Base::$aRequest['operator'].Base::$aRequest['mobile'];
	        }
	        $oUser->DoNewAccount(true);
	
	        return Db::GetRow(Base::GetSql('Customer',array('login'=>$sLogin)));
	    }
	    return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function ShowPopupCart() {
	    Base::$bXajaxPresent=true;
	    $oTable=new Table();
	    
 	    // get list expired history positions
 	    $aExpiredCount = $this->CartExpiredCountPositions();
 	    if ($aExpiredCount > 0) {
			Base::$tpl->assign('sTableMessage',Language::GetText("exist_expired_cart"));
			Base::$tpl->assign('sTableMessageClass','warning_p');
 	    }
 	    if (Auth::$aUser['id'] ){
    	    if(Auth::$aUser['type_']=='manager') {
    	        User::RecalcCart(Auth::$aUser['id_type_price_user'],0);
    	    } else {
    	        User::RecalcCart(Auth::$aUser['id'],1);
    	    }
	    
	   
    	    $sWhere.=" and c.id_user=".Auth::$aUser['id'];
    	   
    	    $aDataCart=Db::GetAll($sSqlCart=Base::GetSql("Part/Search",array(
    	        "type_"=>'cart',
    	        "where"=>$sWhere,
    	    )));
    	    
    	    $aSubtotalCart=Cart::CallParseCart($aDataCart);
    	    $_SESSION['cart']['table_sql']=$sSqlCart;
    	    Base::$tpl->assign('aDataCart',$aDataCart);
    	    Base::$tpl->assign('aSubtotalCart',$aSubtotalCart);
    	    if (empty($aDataCart)){
    	        Base::$oResponse->addScript("$('#icart_id').addClass('empty').html;");
    	        Base::$tpl->assign('sTableMessage',Language::GetMessage("cart empty"));
    	    }else{
    	        Base::$oResponse->addScript("$('#icart_id').removeClass('empty').html;");
    	    }
    	    
    	    $sCartPopup = Base::$tpl->fetch('cart/popup.tpl');
	        MultiLanguage::AddUrlLocaleForOutput($sCartPopup);
    	    Base::$oResponse->addAssign('popup-cart-body','innerHTML',$sCartPopup);
    	    Base::$oResponse->addScript("popupOpen('.js-popup-basket');");
    	    Base::$oResponse->addAssign('icart_id','innerHTML',count($aDataCart));
    	    Base::$oResponse->addScript("$('#user_phone').mask('(099)999-99-99',{placeholder:'_'});");
    	    Base::$oResponse->AddScript("$('#fast_order_button').on( 'click', function() {
              check_phone();
            });");
	    }
	    else {
	        Base::$tpl->assign('sTableMessage',Language::GetMessage("cart empty"));
	        Base::$tpl->assign('sTableMessageClass','warning_p');
	        $sCartPopup = Base::$tpl->fetch('cart/popup.tpl');
	        Base::$oResponse->addAssign('popup-cart-body','innerHTML',$sCartPopup);
	        Base::$oResponse->addScript("popupOpen('.js-popup-basket');");
	    }
	}
	//-----------------------------------------------------------------------------------------------
	//---AOT-40---------------------------------------------------------------------------------------------
	public function CartCreate()
	{
	
	    if(Auth::$aUser['type_']!='manager') Base::Redirect('/?action=cart_cart');
	    if(Base::$aRequest['is_post'])
	    {
	        $aData = Base::$aRequest['data'];
	       
	        if((!$aData['code'] || !$aData['number'] || !$aData['price'])){
	            $sError = 'Please, fill the required fields';
	            Base::$tpl->assign('aData',Base::$aRequest['data']);
	            // break;
	        }
	        elseif(Base::$aRequest['id_cart_package']) {//эту кнопку пока не добавляем на сайт
	            $aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cart_package'])));
	            // Debug::PrintPre($aCartPackage);
// 	            if($aData['id_price']){
// 	                $aUser=Db::GetRow("select uc.*, u.* from user u inner join user_customer uc on u.id=uc.id_user where uc.id_user='".$aCartPackage['id_user']."' ");
// 	                $sSql=Base::GetSql('Catalog/Price',array(
// 	                    'id_price'=>$aData['id_price'],
// 	                    'customer_discount'=>Discount::CustomerDiscount($aUser)
// 	                ));
// 	                $aPrice=Db::GetRow($sSql);
// 	                // Debug::PrintPre($aPrice);
// 	            }
	            $aDataInsert = array();
	            $aDataInsert['code'] = $aPrice['code']?$aPrice['code']:$aData['code'];
	            $aDataInsert['type_'] = 'order';
	            $aDataInsert['id_cart_package'] = Base::$aRequest['id_cart_package'];
	            $aDataInsert['item_code'] = $aPrice['item_code']?$aPrice['item_code']:$aData['pref'].'_'.$aData['code'];
	            $aDataInsert['price'] = $aPrice['price']?$aPrice['price']:$aData['price'];
	            $aDataInsert['price_currency_user'] = $aPrice['price']?$aPrice['price']*$aData['number']:$aData['price']*$aData['number'];
	            $aDataInsert['term'] = $aPrice['term']?$aPrice['term']:$aData['term'];
	            $aDataInsert['number'] = $aData['number'];
	            $aDataInsert['post_date'] = date('Y-m-d H:i:s');
	            $aDataInsert['name_translate'] = $aPrice['name_translate']?$aPrice['name_translate']:$aData['name'];
	            
	            $aDataInsert['id_user'] = $aCartPackage['id_user'];
	            $aDataInsert['price_original']=$aPrice['price_original']?$aPrice['price_original']:$aData['price_original'];
	            $aDataInsert['cat_name'] = $aPrice['cat_name']?$aPrice['cat_name']:Db::GetOne("select name from cat where pref='".$aData['pref']."' ");
	            $aDataInsert['session']=session_id();
	            if(!$aData['id_provider'])
	                $aData['id_provider']=3228;
	            $aDataInsert['id_provider'] = $aPrice['id_provider']?$aPrice['id_provider']:$aData['id_provider'];
	            $aDataInsert['id_provider_ordered'] = $aPrice['id_provider']?$aPrice['id_provider']:$aData['id_provider'];
	            $sProviderName = Db::GetOne("select name from user_provider where id_user='".$aData['id_provider']."' ");
	            if(!$sProviderName)$sProviderName = 'cart_create_manager';
	            $aDataInsert['provider_name'] = $aPrice['provider']?$aPrice['provider']:$sProviderName;
	            $aDataInsert['provider_name_ordered'] = $aPrice['provider']?$aPrice['provider']:$sProviderName;
// 	            $aDataInsert['id_price'] =$aData['id_price'];
	            // Debug::PrintPre($aDataInsert);
	            Db::AutoExecute('cart',$aDataInsert);
	            $iIdCart = Db::InsertId();
	            
	            DB::Execute("insert into cart_log (id_cart, post, order_status, comment, id_user_manager)
					values (".$iIdCart.", UNIX_TIMESTAMP(), 'create_order', '".Language::getMessage('manager_create_item_to_order')."', ".Auth::$aUser['id'].")");
	             
	            //recalc order
	            Manager::SetPriceTotalCartPackage($aDataInsert);
	            // change balance user
	            $iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aDataInsert['id_cart_package']." and operation='pending_work'");
	            if ($iWorkPayAlready)
	            	Finance::Deposit($aDataInsert['id_user'],-(Currency::PrintPrice($aDataInsert['price'],null,0,'<none>')*$aDataInsert['number']),Language::getMessage('manager_create_item_to_order').': '.$iIdCart
	            		,$aDataInsert['id_cart_package'],'internal','cart',0,6,0,'',0,0,true,0,'','',0,$iIdCart);
	             
	            Base::Redirect('/?action=manager_package_edit&id='.Base::$aRequest['id_cart_package']);
	        }
	        else {//нам сюда
// 	            if($aData['id_price']){
// 	                $sSql=Base::GetSql('Catalog/Price',array(
// 	                    'id_price'=>$aData['id_price'],
// 	                    'customer_discount'=>Discount::CustomerDiscount($aUser)
// 	                ));
// 	                $aPrice=Db::GetRow($sSql);
// 	            }
	            $aDataInsert = array();
	            $aDataInsert['code'] = $aPrice['code']?$aPrice['code']:$aData['code'];
	            $aDataInsert['pref'] = $aPrice['pref']?$aPrice['pref']:$aData['pref'];
	            $aDataInsert['item_code'] = $aPrice['item_code']?$aPrice['item_code']:$aData['pref'].'_'.$aData['code'];
	            $aDataInsert['price'] = $aPrice['price']?$aPrice['price']:$aData['price'];
	            $aDataInsert['price_currency_user'] = $aPrice['price']?$aPrice['price']*$aData['number']:$aData['price']*$aData['number'];
	            $aDataInsert['term'] = $aPrice['term']?$aPrice['term']:$aData['term'];
	            $aDataInsert['number'] = $aData['number'];
	            $aDataInsert['post_date'] = date('Y-m-d H:i:s');
	            $aDataInsert['name_translate'] = $aPrice['name_translate']?$aPrice['name_translate']:$aData['name'];
	            
	            $aDataInsert['id_user'] = Auth::$aUser['id'];
	            $aDataInsert['price_original']=$aPrice['price_original']?$aPrice['price_original']:$aData['price_original'];
	            $aDataInsert['cat_name'] = $aPrice['cat_name']?$aPrice['cat_name']:Db::GetOne("select name from cat where pref='".$aData['pref']."' ");
	            $aDataInsert['session']=session_id();
	            if(!$aData['id_provider'])
	                $aData['id_provider']=3228;
	            $aDataInsert['id_provider'] = $aPrice['id_provider']?$aPrice['id_provider']:$aData['id_provider'];
	            $aDataInsert['id_provider_ordered'] = $aPrice['id_provider']?$aPrice['id_provider']:$aData['id_provider'];
	            $sProviderName = Db::GetOne("select name from user_provider where id_user='".$aData['id_provider']."' ");
	            if(!$sProviderName)$sProviderName = 'cart_create_manager';
	            $aDataInsert['provider_name'] = $aPrice['provider']?$aPrice['provider']:$sProviderName;
	            $aDataInsert['provider_name_ordered'] = $aPrice['provider']?$aPrice['provider']:$sProviderName;
// 	            $aDataInsert['provider_name1'] = NULL;
// 	            $aDataInsert['is_manual'] =1;
// 	            $aDataInsert['id_price'] =$aData['id_price'];
	
	            Db::AutoExecute('cart',$aDataInsert);
	            Base::Redirect('/?action=cart_cart');
	        }
	        	
	    }
	    if(Base::$aRequest['id_cart_package']) {
	        $sReturn = 'manager_package_edit&id='.Base::$aRequest['id_cart_package'];
	        $sReturnName = 'Back to order';
	    }
	    Base::$tpl->assign("aPref",array(""=>"")+Db::GetAssoc("Assoc/Pref",array('visible'=>1)));
	    Base::$tpl->assign("aProvider",array("0"=>"")+Db::GetAssoc("select up.id_user as id, up.name as name from user_provider up inner join user as u on u.id=up.id_user and u.visible=1 and u.type_='provider'"));
	    $aData=array(
	        'sWidth'=>"100%",
	        'sHeader'=>"method=post id=cart_create ",
	        'sTitle'=>"Cart Create",
	        'sContent'=>Base::$tpl->fetch('cart/form_cart_create.tpl'),
	        'sSubmitButton'=>'cart_create',
	        'sSubmitAction'=>'cart_create',
	        'sError'=>$sError,
	        'sReturnButton'=>$sReturnName?$sReturnName:'Back to cart',
	        'sReturnAction'=>$sReturn?$sReturn:'cart_cart',
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	    // Debug::PrintPre(Base::$aRequest);
	}
	//-------------------------------------------------------------------------------------------------
	public function CheckLogin()
	{
	    $bChecked=true;
	    if (!preg_match('/^[a-zA-Z0-9_]+$/',Base::$aRequest['login'])) $bChecked=false;
	
	    if ($bChecked) {
	        $aUser=Db::GetRow("select * from user where login='".Base::$aRequest['login']."'");
	        if ($aUser) $bChecked=false;
	    }
	
	    Base::$tpl->assign('bChecked',$bChecked);
	    Base::$oResponse->addAssign('check_login_image_id','innerHTML',Base::$tpl->fetch("user/check_login_image.tpl"));
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderOneClick($aFastOrder, $iIdCart)
	{
	    //для покупки в один клик* order 1 click
	    if (!Base::$aRequest['xajax'])
	        Base::Redirect("/pages/cart_cart");
	
	
	
	    if ($aFastOrder['phone']) {
	        $iUserId      = '';
	        $aCartListNew = Db::GetAll(Base::GetSql("Part/Search", array(
	            "type_" => 'cart',
	            "where" => " and c.id=" . $iIdCart
	        )));
	        // 	        Debug::PrintPre($aCartListNew);
	        if ($aCartListNew) {
	
	            if (!Customer::IsTempUser(Auth::$aUser['login'])) {
	                //normal user
	                $iUserId = Auth::$aUser['id'];
	
	                if (Auth::$aUser['type_'] == 'manager') {
	                    $is_web_order = 0;
	                    //manager
	                    $iUserId      = Db::GetOne("select id from user where login='" . $aFastOrder['phone'] . "' ");
	                    if (!$iUserId) {
	                        Base::$aRequest['data']['phone'] = $aFastOrder['phone'];
	                        Base::$aRequest['data']['email'] = $aFastOrder['email'];
	                        $aOrderUser                      = Cart::AutoCreateUser($aFastOrder['phone']);
	                        $iUserId                         = $aOrderUser['id'];
	                    } else {
	                        // check user is manager
	                        $isCustomer = Db::getOne("Select type_ from user where id=" . $iUserId . " and type_='customer'");
	                        if (!$isCustomer)
	                            Base::Redirect('/?action=cart_cart&table_error=phone_is_manager');
	                    }
	
	                    // recalc cart
	                    User::RecalcCart($iUserId, 1);
	                    // reload cart with new prices
	                    $aCartList = Db::GetAll(Base::GetSql("Part/Search", array(
	                        "type_" => 'cart',
	                        "where" => " and c.id_user=" . Auth::$aUser['id']
	                    )));
	
	                } else {
	                    $is_web_order = 1;
	                    //update user phone
	                    Db::Execute("update user_customer set phone='" . $aFastOrder['phone'] . "', name='" . $aFastOrder['name'] . "'  where id_user='" . $iUserId . "' ");
	                }
	            } else {
	                //check login
	                $iUserId = Db::GetOne("select id from user where login='" . $aFastOrder['phone'] . "' ");
	                if (!$iUserId) {
	                    //guest
	                    $aOrderUser = Cart::AutoCreateUser($aFastOrder['phone']);
	                    $iUserId    = $aOrderUser['id'];
	                    Db::Execute("update user_customer set phone='" . $aFastOrder['phone'] . "', name='" . $aFastOrder['name'] . "'  where id_user='" . $iUserId . "' ");
	
	                }
	            }
	
	            if ($iUserId) {
	                $dPriceTotal = 0;
	                $aUserCartId = array();
	                foreach ($aCartListNew as $aValue) {
	                    $dPriceTotal += Currency::PrintPrice($aValue['price'], null, 2, "<none>") * $aValue['number'];
	                    $aUserCartId[] = $aValue['id'];
	                }
	
	                Base::$aRequest['id_user']=$iUserId;
	                
	                $aCartpackageInsert = array(
	                    'id_manager' => $aOrderUser['id_manager'] ? $aOrderUser['id_manager'] : Auth::$aUser['id_manager'],
	                    'id_user' => $iUserId,
	                    'price_total' => $dPriceTotal + 0,
	                    'order_status' => 'new',
	                    'id_delivery_type' => 0,
	                    'id_payment_type' => 0,
	                    'price_delivery' => 0,
	                    'customer_comment' => Language::GetMessage('order_one_click'),
	                    'is_need_check' => 0,
	                    'id_own_auto' => 0,
	                    'is_one_click' => 1
	                );
	
	                $aCartpackageInsert = String::FilterRequestData($aCartpackageInsert);
	                Db::AutoExecute('cart_package', $aCartpackageInsert);
	                $iCartPackageId = Base::$db->Insert_ID();
	                $aCartPackage   = Db::GetRow(Base::GetSql('CartPackage', array(
	                    'id' => $iCartPackageId
	                )));
	
	                Db::Execute("update cart set type_='order', id_cart_package='$iCartPackageId' ,order_status='pending', id_user='$iUserId'
	                where id in (" . implode(',', $aUserCartId) . ")");
	                Db::Execute("update user set is_temp=0 where login='" . $aFastOrder['phone'] . "'");
	
	                $aAllData   = array(
	                    'phone' => $aFastOrder['phone'],
	                    'cart_package' => $aCartPackage,
	                    'cart_list' => $aCartListNew,
	                    'id_user' => $iUserId
	                );
	                $sAllData   = serialize($aAllData);
	                $iIdUserTmp = substr(md5($aOrderUser['login']), 0, 9);
	                Db::AutoExecute('cart_package_tmp', array(
	                'id_user_tmp' => $iIdUserTmp,
	                'data' => $sAllData
	                ));
	
	                if($aOrderUser){
	                    $_SESSION['new_user']=$aOrderUser  ;
	                } else{
	                    $_SESSION['new_user']=0;
	                }
	                
	                Base::$oResponse->AddScript("window.location.href='/pages/cart_package_one_click_end/" . $iIdUserTmp . "';");
	            }
	        } else {
	            Base::$oResponse->AddScript("window.location.href='/pages/cart_cart';");
	        }
	    } else {
	        Base::$oResponse->AddAssign('id_wrong_number', 'innerHTML', Language::GetMessage('the phone incorrect'));
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderOneClickEnd()
	{
	    $iId_GeneralCurrencyCode = Db::getOne("select id from currency where id=1 ");
	    $iIdUserTmp = trim(strip_tags(Base::$aRequest['id_user']));
	    $sAllData   = Db::GetOne("select data from cart_package_tmp where id_user_tmp='" . $iIdUserTmp . "'");
	
	    if ($iIdUserTmp && $sAllData) {
	
	        Db::Execute("DELETE FROM `cart_package_tmp` WHERE `cart_package_tmp`.`id_user_tmp` ='" . $iIdUserTmp . "'");
	
	        $aAllData     = unserialize($sAllData);
	        $sPhone       = $aAllData['phone'];
	        $aCartPackage = $aAllData['cart_package'];
	        $aCartListNew = $aAllData['cart_list'];
	        $iUserId      = $aAllData['id_user'];
	
	        $aOrderUser = Db::GetRow($s = "select u.email as email, u2.email as manager_email
	            from user as u
	            inner join user_customer as uc on uc.id_user=u.id
	            inner join user as u2 on u2.id=uc.id_manager
	            where u.id='" . $iUserId . "'");
	
	        $bWork     = false;
	        $sWorkDays = Base::GetConstant('global:work_days', '1,2,3,4,5,6');
	        $sWorkTime = Base::GetConstant('global:work_time', '9.00-19.00');
	        $iCurDay   = date('N', time());
	
	        if (strpos($sWorkDays, $iCurDay) !== false) {
	            $bCheckTime = false;
	            $iCurTime   = date('G.i', time());
	            $aWorkTime  = explode(';', $sWorkTime);
	            if (count($aWorkTime) > 1)
	                foreach ($aWorkTime as $iK => $sVal) {
	                    if ($bCheckTime)
	                        break;
	                    if ($iK == 0)
	                        continue;
	                    list($iTDay, $sTTime) = explode(':', $sVal);
	                    if ($iTDay == $iCurDay) {
	                        $bCheckTime = true;
	                        list($iT1, $iT2) = explode('-', $sTTime);
	                        if ($iCurTime > $iT1 && $iCurTime < $iT2)
	                            $bWork = true;
	                    }
	                }
	            if (!$bCheckTime) {
	                list($iT1, $iT2) = explode('-', $aWorkTime[0]);
	                if ($iCurTime > $iT1 && $iCurTime < $iT2)
	                    $bWork = true;
	            }
	        }
	
	        if ($bWork) {
	            $aTextTemplate = String::GetSmartyTemplate('order_one_click_work_end', array(
	                'sPhone' => $sPhone,
	                'aCartPackage' => $aCartPackage,
	                'aCart' => $aCartListNew
	            ));
	        } else {
	            $aTextTemplate = String::GetSmartyTemplate('order_one_click_rest_end', array(
	                'sPhone' => $sPhone,
	                'aCartPackage' => $aCartPackage,
	                'aCart' => $aCartListNew
	            ));
	        }
	
	
	        //order table section
	
	        $aUserCart=$aCartListNew;
	        if ($aUserCart) foreach ($aUserCart as $iKey => $aValue) {
	            $dSubtotal+=$aValue['number']*Currency::PrintPrice($aValue['price']);
	            $aUserCart[$iKey]['number_price'] = $aValue['number']*Currency::PrintPrice($aValue['price']);
	            $aUserCart[$iKey]['print_price_user'] = Currency::PrintPrice($aValue['price'],null,2,"<none>");
	            $aUserCart[$iKey]['print_price'] = Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>");
	        }
	        // 	        $aCartPackage['liqpay_html'] = Payment::LiqPayGetFormOrder($aValue['id'],$aValue['price_total']);
	        Cart::CallParseCart($aUserCart);
	        Base::$tpl->assign('aUserCart',$aUserCart);
	        Base::$tpl->assign('dSubtotal',$dSubtotal);
	        Base::$tpl->assign('dTotal',$dSubtotal+Currency::PrintPrice($_SESSION['current_cart']['price_delivery']));
	
	        // 	        Debug::PrintPre($aUserCart);
	        Base::$sText .= $aTextTemplate['parsed_text'];
	        Base::$sText=Base::$tpl->fetch('cart/text_fast_order.tpl');
	        Base::$oContent->addCrumb('Заказ успешно оформлен');
	
	
	
	
// 	        if ($aAllData['cart_package']['email'] || $aAllData['cart_package']['login'] ) {
// 	            if($_SESSION['new_user']!=0){
// 	                $aSmartyTemplate = String::GetSmartyTemplate('cart_package_details_order_one_click_new_customer', array(
// 	                    'aCartPackage' => $aCartPackage,
// 	                    'aCart' => $aUserCart,
// 	                    'aUser' => $_SESSION['new_user']
// 	                ));
// 	                Mail::AddDelayed($aAllData['cart_package']['email']?$aAllData['cart_package']['email']:$aAllData['cart_package']['login'], Language::GetText("cart_package_details_order_one_click_new_customer").' '.$aCartPackage['id'], $aSmartyTemplate['parsed_text'], '', Base::GetConstant('mail:from_name', 'autohaus'), false);
// 	            }else{
// 	                $aSmartyTemplate = String::GetSmartyTemplate('cart_package_details_order_one_click', array(
// 	                    'aCartPackage' => $aCartPackage,
// 	                    'aCart' => $aUserCart
// 	                ));
// 	                Mail::AddDelayed($aAllData['cart_package']['email']?$aAllData['cart_package']['email']:$aAllData['cart_package']['login'], Language::GetText("cart_package_details_order_one_click").' '.$aCartPackage['id'], $aSmartyTemplate['parsed_text'], '', Base::GetConstant('mail:from_name', 'autohaus'), false);
// 	            }
// 	        }
	
	        if (Base::GetConstant("manager:enable_order_notification_on_email", "1")) {
	            $aSmartyTemplate = String::GetSmartyTemplate('manager_mail_order_one_click', array(
	                'aCartPackage' => $aCartPackage,
	                'aCart' => $aUserCart
	            ));
	            Mail::AddDelayed(Language::getConstant('global:to_email'). ", " . Base::GetConstant('manager:email_recievers', 'info@mstarproject.com'), $aSmartyTemplate['name'] . " " . $aCartPackage['id'], $aSmartyTemplate['parsed_text'], '', Base::GetConstant('mail:from_name', 'autohaus'), false);
	        }
	
	        if ($aCartPackage['id'] && Finance::HaveMoney($aCartPackage['price_total'], $aCartPackage['id_user'])) {
	            $this->SendPendingWork($aCartPackage['id']);
	        }
	
	    } else
	        Base::Redirect("/pages/cart_cart");
	}
	//-----------------------------------------------------------------------------------------------
}
?>