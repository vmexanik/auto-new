<?
/**
 * @author Mikhail Starovoyt
 *
 */
class User extends Base
{
	public static $aErrorTr=array();

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Repository::InitDatabase('user',false);

		Base::$aData['template']['bWidthLimit']=true;
		//Manual::ShowShort('CUS');
		Base::$bXajaxPresent=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Login()
	{
		$oAuth=new Auth();
		$oAuth->Logout();
		Base::$oContent->AddCrumb(Language::GetMessage('Enter'),'');
		Base::$sText.=Base::$tpl->fetch('user/login.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function DoLogin()
	{
		$aOldUser=Auth::$aUser;
		$oAuth=new Auth();
		$aUser=$oAuth->Login($_POST['login'],$_POST['password'],false,true
		,Base::GetConstant('user:is_salt_password',1));
		
		if(!$aUser) {
		    MultiLanguage::Redirect("/pages/user_login/?error_login=1");
		    //Base::Redirect("/?action=user_login&error_login=1");
		}

		Db::AutoExecute('user',array('password_temp'=>''),'UPDATE',"id='".$aUser['id']."'");
		if ($aUser['type_']=='customer') {
			if ($aOldUser && Customer::IsChangeableLogin($aOldUser['login'])) {
				//Syncronization
				Db::Execute("update cart set id_user='".$aUser['id']."' where id_user='".$aOldUser['id']."'");
				Db::Execute("update cart_package set id_user='".$aUser['id']."' where id_user='".$aOldUser['id']."'");
				Db::Execute("update user set visible='0' where id='".$aOldUser['id']."'");
				Db::Execute("update user_auto set id_user='".$aUser['id']."' where id_user='".$aOldUser['id']."'");
			}
			MultiLanguage::Redirect("/pages/dashboard/");
		}


		MultiLanguage::Redirect("/");
	}
	//-----------------------------------------------------------------------------------------------
	public function UloginLogin()
	{
		$oAuth=new Auth();

		$sJsonResult = file_get_contents('http://ulogin.ru/token.php?token=' . Base::$aRequest['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		//$sJsonResult='{"access_token":"ya29.AHES6ZREFYc5VObrmoKu-3EP6GhPjzsgbPdLSC3CrZ15UC4","network":"google","identity":"https:\/\/plus.google.com\/u\/0\/100142627275839704974\/","uid":"100142627275839704974","email":"zaman.ua@gmail.com","nickname":"ZHenya","first_name":"\u0416\u0435\u043d\u044f","last_name":"\u041b\u0430\u0437\u0430\u0440\u0435\u0432","profile":"https:\/\/plus.google.com\/u\/0\/100142627275839704974\/","manual":"nickname"}';
		$aJsonResult = json_decode($sJsonResult, true);
		$aResult=Db::GetRow("select * from ulogin_user where identity='".$aJsonResult['identity']."'");
		if ($aResult){
			$sLogin=$aResult['login'];
			$sPassword=$aJsonResult['uid'];
			$aUser=$oAuth->Login($sLogin,$sPassword,false,true,Base::GetConstant('user:is_salt_password',1));
			Base::Redirect("./");
		}
		else {
			$sNick=Catalog::StripLogin($aJsonResult['nickname']);

			$sName=$aJsonResult['first_name']." ".$aJsonResult['last_name'];
			if (!$sNick)
			    $sNick = Content::Translit(Catalog::StripLogin($sName));
			//write ulogin data to db
			Db::Execute("INSERT INTO `ulogin_user` (`uid`, `nickname`, `provider`, `full_name`, `identity`)
					VALUES(
					'".$aJsonResult['uid']."',
					'".$sNick."',
					'".$aJsonResult['network']."',
					'".$sName."',
					'".$aJsonResult['identity']."'
					)");
			$sLogin="lz".Db::InsertId()."-".$sNick;
			//$sPassword=trim("passwd".Db::InsertId());  //, password='".$sPassword."
			Db::Execute("update ulogin_user set login='".$sLogin."' where identity='".$aJsonResult['identity']."'");

			$sEmail=$aJsonResult['email'];
			$sIdentity=$aJsonResult['identity'];
			$sPassword=$aJsonResult['uid'];
			//emulate POST data
			$_POST['login'] = $sLogin;
			$_POST['password'] = $sPassword;

			//default registration
			$sSignature=md5(time()."autozp_customer".$sLogin.$sEmail);
			$sIp=Auth::GetIp();
			$sSalt=String::GenerateSalt();

			$sQuery="insert into user(type_,login,password,email,visible,approved,signature,ip,id_language,last_visit_date,salt
				,password_temp) values
			 ('customer','".$sLogin."','".String::Md5Salt($sPassword,$sSalt)."'
			 	,'".$sEmail."','1','0','".$sSignature."','".$sIp."','".Language::$iLocale."',NOW(),'".$sSalt."'
			 	,'".$sPassword."')";
			Db::Execute($sQuery);
			$sIdUser=Db::InsertId();
			if ($sIdUser) {

				if (!$aManager) $aManager=Db::GetRow("select u.*,um.* from user_manager um,user u
					where u.id=um.id_user and u.visible=1 and um.has_customer=1 order by rand()");

				$aUserCustomer=array(
				'name'=>$sName
				);
				$aUserCustomer['id_user']=$sIdUser;
				$aUserCustomer['id_manager']=$aManager['id'];
				Db::Autoexecute('user_customer',$aUserCustomer);


				$sQuery="insert into user_account(id_user) values ('$sIdUser')";
				Db::Execute($sQuery);

				if ($bAutoCreate) return true;

				$sLink="<A href='http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$sSignature."'
					>".Base::$language->getMessage('Confirm')."</a>";
				$sUrl="http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$sSignature;

				$aData=array(
				'info'=>array(
				'link'=>$sLink,
				'url'=>$sUrl,
				'email'=>$sEmail,
				'provider'=>$aJsonResult['network']
				),
				'aManager'=>$aManager
				);
				$aSmartyTemplate=String::GetSmartyTemplate('confirmation_letter_ulogin', $aData);
				$sBody=$aSmartyTemplate['parsed_text'];

				Mail::AddDelayed($sEmail,Base::$language->getMessage('Confirmation Letter'),$sBody,'','',true,2);

				$sQuery="update ulogin_user set user_id='$sIdUser', create_done=1 where identity='".$sIdentity."'";
				Db::Execute($sQuery);

				$this->DoLogin();
				Base::Redirect("/pages/dashboard/");
			}
			else {
				Base::$sText.=String::GetSmartyTemplate('new_account_error_created');
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Logout()
	{
		$oAuth=new Auth();
		$oAuth->Logout();

		MultiLanguage::Redirect('/pages/user_login/');
	}
	//-----------------------------------------------------------------------------------------------
	public function NewAccount()
	{
		if (Base::$aRequest['is_post']) {
		   
			$sError=$this->NewAccountError();
			
			 $capcha = $_POST['g-recaptcha-response'];
		    $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
		    $secret_key = Language::GetConstant('captcha:privat_key','6LdJj9UUAAAAAAEI9b67EP3ZJJhu10MvBgNTJ2bs');
		    $query = $url_to_google_api . '?secret=' . $secret_key . '&response=' . $capcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
		    $data = json_decode(file_get_contents($query));
			if (!$data->success)
			    $sError = "Check capcha value";
			else {
			if (!$sError) {
				$this->DoNewAccount();
				return;
			 }
			}
		}

// 		if (Base::$aRequest['second_time']) {
// 			Base::$tpl->assign('sSecondTime',$sSecondTime=1);
// 			//restore captcha
// 			$aCapcha=array(
// 				'mathematic_formula' => Base::$aRequest['capcha']['mathematic_formula'],
// 				'validation_hash' => Base::$aRequest['capcha']['validation_hash'],
// 				'result' => Base::$aRequest['capcha']['result'],
// 			);
// 			Base::$tpl->assign('aCapcha',$aCapcha);
// 			Base::$tpl->assign('sCapcha',Base::$tpl->fetch('user/capcha.tpl'));
// 		} else {
// 			$oCpacha= new Capcha();
// 			Base::$tpl->assign('sCapcha',$oCpacha->GetMathematic('user/capcha.tpl'));
// 			Base::$tpl->assign('sSecondTime',$sSecondTime=1);
// 		}
		
		Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
		    '1'=>Language::GetMessage('частное лицо'),
		    '2'=>Language::GetMessage('юридическое лицо')
		));
		$aEntityType=explode(",",Language::GetConstant('user:entity_type','ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
		Base::$tpl->assign('aEntityType',$aEntityType);

		Resource::Get()->Add('/css/user.css');
		Resource::Get()->Add('/js/user.js',2);
		
// 		Base::$aMessageJavascript = array(
// 		    'empty'=> Language::GetMessage("password strength"),
// 		    'short'=> Language::GetMessage("password strength:short"),
// 		    "bad"=> Language::GetMessage("password strength:bad"),
// 		    "good"=> Language::GetMessage("password strength:good"),
// 		    "strong"=> Language::GetMessage("password strength:strong"),	
// 		    "mismatch"=> Language::GetMessage("password strength:mismatch"),
// 		);
		
// 		if ($sSecondTime) $aField['second_time']=array('type'=>'hidden','name'=>'second_time','value'=>'1');
// 		$aField['login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['login'],'name'=>'login','szir'=>1,'id'=>'user_login','onblur'=>"javascript: xajax_process_browse_url('?action=user_check_login&login='+this.value); return false;",'add_to_td'=>array(
// 		    'check_login'=>array('type'=>'span','id'=>'check_login_image_id','value'=>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
// 		),'style'=>' ');
// 		$aField['password']=array('title'=>'Password','type'=>'password','value'=>Base::$aRequest['password'],'name'=>'password','szir'=>1,'id'=>'pass1','style'=>' ');
// 		$aField['verify_password']=array('title'=>'Retype Password','type'=>'password','value'=>Base::$aRequest['verify_password'],'name'=>'verify_password','szir'=>1,'id'=>'pass2','style'=>' ');
// 		$aField['password_strength']=array('class'=> 'pass-indicator','title'=>'password strength','type'=>'span','id'=>'pass-strength-result','contexthint'=>'password_strength','style'=>' ');
// 		$aField['email']=array('title'=>'Email','type'=>'input','value'=>Base::$aRequest['email'],'name'=>'email','szir'=>1,'style'=>' ');
		
// 		foreach ($aEntityType as $aValue){
// 		    $aEntityTypeOptions[$aValue]=$aValue;
// 		}
		
// 		$iIdCustomerType=Base::$aRequest['data']['id_user_customer_type']?Base::$aRequest['data']['id_user_customer_type']:Auth::$aUser['id_user_customer_type'];
		
// 		$aField['user_customer_type_id']=array('title'=>'User customer type','type'=>'select','options'=>$aUserCustomerType,'selected'=>$iIdCustomerType,
// 		    'name'=>'data[id_user_customer_type]','onchange'=>"oUser.ToggleEntityTr($('#user_customer_type_id').val())",'id'=>'user_customer_type_id','style'=>' ');
// 		$aField['entity_type']=array('title'=>'Entity name','type'=>'select','options'=>$aEntityTypeOptions,'selected'=>(Auth::$aUser['entity_type']!='')?Auth::$aUser['entity_type']:Base::$aRequest['data']['entity_type'],'name'=>'data[entity_type]','tr_id'=>'entity_tr_id','add_to_td'=>array(
// 		    'entity_name'=>array('type'=>'input','value'=>Auth::$aUser['entity_name']?Auth::$aUser['entity_name']:Base::$aRequest['data']['entity_name'],'name'=>'data[entity_name]','tr_class'=>'entity_tr_id')
// 		),'style'=>' ');
// 		$aField['additional_field1']=array('title'=>'additional_field1','type'=>'input','value'=>Auth::$aUser['additional_field1']?Auth::$aUser['additional_field1']:Base::$aRequest['data']['additional_field1'],'name'=>'data[additional_field1]','tr_id'=>'additional_field1_tr_id','style'=>' ');
// 		$aField['additional_field2']=array('title'=>'additional_field2','type'=>'input','value'=>Auth::$aUser['additional_field2']?Auth::$aUser['additional_field2']:Base::$aRequest['data']['additional_field2'],'name'=>'data[additional_field2]','tr_id'=>'additional_field2_tr_id','style'=>' ');
// 		$aField['additional_field3']=array('title'=>'additional_field3','type'=>'input','value'=>Auth::$aUser['additional_field3']?Auth::$aUser['additional_field3']:Base::$aRequest['data']['additional_field3'],'name'=>'data[additional_field3]','tr_id'=>'additional_field3_tr_id','style'=>' ');
// 		$aField['additional_field4']=array('title'=>'additional_field4','type'=>'input','value'=>Auth::$aUser['additional_field4']?Auth::$aUser['additional_field4']:Base::$aRequest['data']['additional_field4'],'name'=>'data[additional_field4]','tr_id'=>'additional_field4_tr_id','style'=>' ');
// 		$aField['additional_field5']=array('title'=>'additional_field5','type'=>'input','value'=>Auth::$aUser['additional_field5']?Auth::$aUser['additional_field5']:Base::$aRequest['data']['additional_field5'],'name'=>'data[additional_field5]','tr_id'=>'additional_field5_tr_id','style'=>' ');
// 		$aField['name']=array('title'=>'FLName','type'=>'input','value'=>Base::$aRequest['data']['name']?Base::$aRequest['data']['name']:Auth::$aUser['name'],'name'=>'data[name]','szir'=>1,'style'=>' ');
// 		$aField['city']=array('title'=>'City','type'=>'input','value'=>Base::$aRequest['data']['city']?Base::$aRequest['data']['city']:Auth::$aUser['city'],'name'=>'data[city]','szir'=>1,'style'=>' ');
// 		$aField['address']=array('title'=>'Address','type'=>'input','value'=>Base::$aRequest['data']['address']?Base::$aRequest['data']['address']:Auth::$aUser['address'],'name'=>'data[address]','szir'=>1,'style'=>' ');
// 		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Base::$aRequest['phone']?Base::$aRequest['phone']:Auth::$aUser['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1,'style'=>' ');
// 		$aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>Base::$aRequest['data']['remark']?Base::$aRequest['data']['remark']:Auth::$aUser['remark'],'style'=>' ');
		 
// 		if($iIdCustomerType!=''){
// 		    if($iIdCustomerType==1)
// 		    {
// 		        $aField['entity_type']['tr_style']="display:none;";
// 		        $aField['entity_name']['tr_style']="display:none;";
// 		        $aField['additional_field1']['tr_style']="display:none;";
// 		        $aField['additional_field2']['tr_style']="display:none;";
// 		        $aField['additional_field3']['tr_style']="display:none;";
// 		        $aField['additional_field4']['tr_style']="display:none;";
// 		        $aField['additional_field5']['tr_style']="display:none;";
// 		    }
// 		} else {
// 		    if($iIdCustomerType==1 || !Base::$aRequest['data']['id_user_customer_type'])
// 		    {
// 		        $aField['entity_type']['tr_style']="display:none;";
// 		        $aField['entity_name']['tr_style']="display:none;";
// 		        $aField['additional_field1']['tr_style']="display:none;";
// 		        $aField['additional_field2']['tr_style']="display:none;";
// 		        $aField['additional_field3']['tr_style']="display:none;";
// 		        $aField['additional_field4']['tr_style']="display:none;";
// 		        $aField['additional_field5']['tr_style']="display:none;";
// 		    }
// 		}
		/*$aField['capcha']=array('title'=>'Capcha field','type'=>'text','szir'=>1,'value'=>Base::$tpl->fetch('addon/capcha/mathematic.tpl'),'style'=>' ');
		$aField['user_agreement']=array('type'=>'checkbox','name'=>'user_agreement','value'=>'1','checked'=>Base::$aRequest['user_agreement'],'colspan'=>2,'add_to_td'=>array(
		    'i_agree'=>array('type'=>'text','value'=>Language::GetMessage('I agree to').' '."<a href='/pages/agreement' target=_blank>".' '.Language::GetMessage('User agreement'))
		),'style'=>' ');
		*/
// 		$oCapcha = Base::$tpl->fetch('addon/capcha/mathematic.tpl');
// 		$oUserAgreement =  Base::$tpl->fetch('addon/capcha/mathematic.tpl');
// 		Base::$tpl->assign('oCapcha',$oCapcha);
// 		Base::$tpl->assign('oUserAgreement',$oUserAgreement);
		$aData=array(
		'sHeader'=>"method=post",
		'sContent'=>Base::$tpl->fetch('user/new_account.tpl'),
// 		'aField'=>$aField,
// 		'bType'=>'generate',
// 		'sGenerateTpl' =>  'user/genere_new_account_form.tpl',
	    'sTemplatePath' =>'form/main_reg.tpl',
		'sSubmitButton'=>'Register',
		'sSubmitAction'=>'user_new_account',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$oContent->AddCrumb(Language::GetMessage('Register'),'');
		Base::$tpl->assign('oForm',$oForm->getForm());
		Base::$sText.=Base::$tpl->fetch('user/registration.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function NewAccountError()
	{
		if (!preg_match('/^[a-zA-Z0-9_]+$/',Base::$aRequest['login']))
		return "Login must contain only latin letters and numbers";

		if (!Base::$aRequest['user_agreement'])
		return "You need to apply user agreemnt";

		if (!Base::$aRequest['login']||!Base::$aRequest['password']
		|| !Base::$aRequest['data']['phone'] || !Base::$aRequest['data']['name'])
		return "Please, enter all the fields";

		if (Base::$aRequest['password']!=Base::$aRequest['verify_password'])
		return "Passwosds are different. Please try again";

		if (Base::$aRequest['password']==Base::$aRequest['login'])
		return "Login and password must be different. Please try again";

		if (strlen(Base::$aRequest['password'])<4)
		return "Password can't be less then 4 digits";
		
        if(Base::$aRequest['email']){
    		if (!String::CheckEmail(Base::$aRequest['email']))
    		return "Please, check your email";
        }
        
		$sQuery="select * from user where login='".Base::$aRequest['login']."'";
		$aUser=Db::GetRow($sQuery);
		if ($aUser)	return "This login is already occupied. Please choose different one.";

		if(Base::$aRequest['email']){
    		$sQuery="select * from user where email='".Base::$aRequest['email']."'";
    		$aUser=Db::GetRow($sQuery);
    		if ($aUser)	{
    			if (Customer::IsChangeableLogin($aUser['login'])) {
    				return 'disable_temp_user';
    			}
    			else
    			return "This email is already registered. Please use the link \"Forgot password\".";
    		}
		}
// 		if (!Capcha::CheckMathematic()) return "Check capcha value";

		return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function DoNewAccount($bAutoCreate=false)
	{
		$sSignature=md5(time()."autozp_customer".Base::$aRequest['login'].Base::$aRequest['email']);
		$sIp=Auth::GetIp();
		$sSalt=String::GenerateSalt();
		$bIsTemp=($bAutoCreate ? 1 : 0);

		$sQuery="insert into user(type_,login,password,email,visible,approved,signature,ip,id_language,last_visit_date,salt
			,password_temp,is_temp) values
		 ('customer','".Base::$aRequest['login']."','".String::Md5Salt(Base::$aRequest['password'],$sSalt)."'
		 	,'".Base::$aRequest['email']."','1','0','".$sSignature."','".$sIp."','".Language::$iLocale."',NOW(),'".$sSalt."'
		 	,'".Base::$aRequest['password']."','".$bIsTemp."')";
		Db::Execute($sQuery);
		$sIdUser=Db::InsertId();
		if ($sIdUser) {

			if(Auth::$aUser['type_']=='manager') $aManager=Db::GetRow("select u.*,um.* from user_manager um,user u
				where u.id=um.id_user and u.id='".Auth::$aUser['id']."'");
			if (!$aManager) $aManager=Db::GetRow("select u.*,um.* from user_manager um,user u
				where u.id=um.id_user and u.visible=1 and um.has_customer=1 order by rand()");

			$aUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
			'name','country','city','address','address2','zip','phone','phone2','remark'
			,'additional_field5','additional_field2','additional_field3','additional_field4'
			,'id_user_customer_type','entity_type','entity_name','additional_field1'
			));
			$aUserCustomer['id_user']=$sIdUser;
			
			if(Base::$aRequest['is_binotel_sync']){
			     $aUserCustomer['is_binotel_sync']=1;
			     $aUserCustomer['id_binotel_user']=Base::$aRequest['id_binotel'];
			}
			if(Base::$aRequest['phone2'])$aUserCustomer['phone2']=Base::$aRequest['phone2'];
			if(Base::$aRequest['phone3'])$aUserCustomer['phone3']=Base::$aRequest['phone3'];
			
			// not set visible manager with klient flag
			if (!$aManager['id'])
				$aManager['id'] = 0;
			
			$aUserCustomer['id_manager']=$aManager['id'];
			Db::Autoexecute('user_customer',$aUserCustomer);


			$sQuery="insert into user_account(id_user) values ('$sIdUser')";
			Db::Execute($sQuery);

			if ($bAutoCreate) return $sIdUser;

			$sLink="<A href='http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$sSignature."'
				>".Base::$language->getMessage('Confirm')."</a>";
			$sUrl="http://".SERVER_NAME."/?action=user_confirm_registration&signature=".$sSignature;

			$aData=array(
			'info'=>array(
			'link'=>$sLink,
			'url'=>$sUrl,
			'login'=>Base::$aRequest['login'],
			'password'=>Base::$aRequest['password'],
			'email'=>Base::$aRequest['email'],
			),
			'aManager'=>$aManager
			);
			$aSmartyTemplate=String::GetSmartyTemplate('confirmation_letter', $aData);
			$sBody=$aSmartyTemplate['parsed_text'];

			Mail::AddDelayed(Base::$aRequest['email'],Base::$language->getMessage('Confirmation Letter'),$sBody,'','',true,2);

			if(Language::getConstant('manager_send_mail_for_new_user',1)) {
				$aData=array(
					'info'=>array(
							'login'=>Base::$aRequest['login'],
							'email'=>Base::$aRequest['email'],
					),
				);
				$aSmartyTemplate=String::GetSmartyTemplate('manager_create_new_customer', $aData);
				$sBody=$aSmartyTemplate['parsed_text'];
			
				Mail::AddDelayed(Language::getConstant('global:to_email'),$aSmartyTemplate['name'].' - '.Base::$aRequest['login'],$sBody,'','',true,2);
			}
			if(Base::$aRequest['is_binotel_sync']) Base::Redirect("http://".SERVER_NAME."/pages/binotel_users");
			$this->DoLogin();
		}
		else {
			Base::$sText.=String::GetSmartyTemplate('new_account_error_created');
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ConfirmRegistration()
	{
		if (Base::$aRequest['signature']) {
			$sQuery="update user set approved=1 where signature='".Base::$aRequest['signature']."'";
			Db::Execute($sQuery);
			if (Base::$db->Affected_Rows()) {
				Base::$sText.=Language::GetText('approve_text');
				return;
			}
		}
		Base::$sText.=Language::GetText('approve_error');
	}
	//-----------------------------------------------------------------------------------------------
	public function RestorePassword()
	{
		if (Base::$aRequest['is_post'] && !$sError) {

			if (!Base::$aRequest['email'] && !Base::$aRequest['login']) $sError="Empty fields";

			if (Base::$aRequest['login']) $sWhere.=" and login='".Base::$aRequest['login']."'";
			if (Base::$aRequest['email']) $sWhere.=" and email='".Base::$aRequest['email']."'";

			$aUser=Db::GetRow("select * from user where 1=1 ".$sWhere);
			if (!$sError && $aUser['id']) {
				if (Customer::IsChangeableLogin($aUser['login'])) {
					$sError='disable_temp_user';
				}
				else {
					$aSmartyTemplate=String::GetSmartyTemplate('restore_password_sent', $aData);
					Base::$sText.=$aSmartyTemplate['parsed_text'];
	
					$sRestoreCode=md5(Base::GetConstant('global:project_name').$aUser['id'].$aUser['salt']);
					$sLink="<A href='http://".SERVER_NAME."/?action=user_new_password&id=".$aUser['id']."&restore_code=".$sRestoreCode."'
					>".Language::GetMessage('Create new password')."</a>";
					$sUrl="http://".SERVER_NAME."/?action=user_new_password&id=".$aUser['id']."&restore_code=".$sRestoreCode;
	
					$aData=array(
					'aUser'=>$aUser,
					'sLink'=>$sLink,
					'sUrl'=>$sUrl,
					);
					$aTemplate=String::GetSmartyTemplate('restore_password', $aData);
					$sBody=$aTemplate['parsed_text'];
	
					Mail::SendNow($aUser['email'],$aTemplate['name'],$sBody);
	
					return;
				}
			}
			if (!$sError) $sError="There is no such a record in our user database.";
		}

		$aField['login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['login'],'name'=>'login');
		$aField['or']=array('type'=>'text','value'=>Language::GetMessage('OR'));
		$aField['email']=array('title'=>'Your email','type'=>'input','value'=>Base::$aRequest['email'],'name'=>'email');
		
		Base::$oContent->AddCrumb(Language::GetMessage('new password form'),'');
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Forgot Password",
		//'sContent'=>Base::$tpl->fetch('user/restore_password.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Send',
		'sSubmitAction'=>'user_restore_password',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function NotifyConfirmedProfileFill($iIdUser)
	{
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array('id'=>$iIdUser)));

		if (!$aCustomer) return;
		if ($aCustomer['profile_notified']) return;

		Message::CreateNotification($aCustomer['login'],'profile_fill_notification','customer');
		Message::AddNote($aCustomer['id'],Language::GetMessage('profile_fill_notification')
		,String::GetSmartyTemplate('profile_fill_notification'));
		Db::Execute("update user_customer set profile_notified='1' where id_user='{$aCustomer['id']}'");

	}
	//-----------------------------------------------------------------------------------------------
	public function ChangePassword()
	{
		Auth::NeedAuth();
		if (Base::$aRequest['is_post']) {

			if (!Base::$aRequest['data']['new_password'] ||  !Base::$aRequest['data']['old_password']
			|| !Base::$aRequest['data']['retype_new_password'])
			$sError='Please fill out all fields';

			if (strlen(trim(Base::$aRequest['data']['new_password']))<=5 && !$sError)
			$sError='Password must more than 5 digits';

			if (Base::$aRequest['data']['new_password']!=Base::$aRequest['data']['retype_new_password'] && !$sError)
			$sError='Passwords are not the same';

			$aUser=Db::GetRow(Base::GetSql('User',array('login'=>Auth::$aUser['login'])));
			if ($aUser['password'] !=String::Md5Salt(trim(Base::$aRequest['data']['old_password']),$aUser['salt']) && !$sError)
			$sError='Please, check the old password';

			if (!$sError) {
				$sSalt=String::GenerateSalt();
				$aUserUpdate=array(
				'password'=>String::Md5Salt(Base::$aRequest['data']['new_password'],$sSalt),
				'salt'=>$sSalt,
				);
				Db::AutoExecute('user',$aUserUpdate,"UPDATE"," login='".Auth::$aUser['login']."'");
				$sError=Language::GetMessage('You have successfully changed your password');
			}
		}

		$aField['old_password']=array('title'=>'Old password','type'=>'password','value'=>Base::$aRequest['data']['old_password'],'name'=>'data[old_password]');
		$aField['new_password']=array('title'=>'New password','type'=>'password','value'=>Base::$aRequest['data']['new_password'],'name'=>'data[new_password]');
		$aField['retype_new_password']=array('title'=>'Retype new password','type'=>'password','value'=>Base::$aRequest['data']['retype_new_password'],'name'=>'data[retype_new_password]');
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Change Password Form",
		'sWidth'=>"350px",
		//'sContent'=>Base::$tpl->fetch('user/form_change_password.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Update',
		'sSubmitAction'=>'user_change_password',
		'sReturnButton'=>'Return to profile',
		'sReturnAction'=>Auth::$aUser['type_'].'_profile',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeLogin()
	{
		Auth::NeedAuth();

		if (!Customer::IsChangeableLogin(Auth::$aUser['login'])) return;

		if (Base::$aRequest['is_post']) {
			$sNewLogin = trim(Base::$aRequest['data']['new_login']);
			// check if not exist
			$aUser=Db::GetRow("select * from user where login='".$sNewLogin."'");
			if ($aUser){
				$sError=Language::GetMessage('This login already exist');
			}else {
				if (strlen($sNewLogin)==0 || (Auth::$aUser['login'] == $sNewLogin) ){
					$sError='Incorrect new login';
				}else {
					$aUser=Db::GetRow("select * from user where login='".Auth::$aUser['login']."'");
					if (!$sError) {
						//[----- UPDATE -----------------------------------------------------]
						Db::Execute("update user_customer set is_locked='1' where id_user='".Auth::$aUser['id']."'");
						$sQuery="update user set login='".$sNewLogin."' where id='".Auth::$aUser['id']."' ";
						if ($aUser['has_forum']){
							//require(SERVER_PATH.'/class/module/Forum.php');
							Forum::ChangeLogin($aUser, $sNewLogin);
						}

						//[----- END UPDATE -------------------------------------------------]
						$bResult = Db::Execute($sQuery);
						if ($bResult) {
							$sError=Language::GetMessage('You have successfully changed your login');
							Auth::NeedAuth();
						}
						else $sError=Language::GetMessage('Error during changed your login');
					}
				}
			}
		}
		Base::$tpl->assign('old_login',$old_login=Auth::$aUser['login']);

		$aField['old_login']=array('title'=>'Old login','type'=>'text','value'=>$old_login);
		$aField['new_login']=array('title'=>'New login','type'=>'input','value'=>'','name'=>'data[new_login]');
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Change Login Form",
		'sWidth'=>"350px",
		//'sContent'=>Base::$tpl->fetch('user/form_change_login.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Update',
		'sSubmitAction'=>'user_change_login',
		'sReturnButton'=>'Return to profile',
		'sReturnAction'=>Auth::$aUser['type_'].'_profile',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function NewPassword()
	{
		$aUser=Db::GetRow(Base::GetSql('User',array(
		'id'=>(Base::$aRequest['id'] ? Base::$aRequest['id']:'-1')
		)));
		$sRestoreCode=md5(Base::GetConstant('global:project_name').$aUser['id'].$aUser['salt']);

		if (!$aUser	|| Base::$aRequest['restore_code']!=$sRestoreCode) {
			Base::$sText.=Language::GetText('User not found or restore_code is incorrect error');
			return;
		}

		if (Base::$aRequest['is_post']) {

			if (!Base::$aRequest['data']['new_password'] || !Base::$aRequest['data']['retype_new_password'])
			$sError='Please fill out all fields';

			if (strlen(trim(Base::$aRequest['data']['new_password']))<=5 && !$sError)
			$sError='Password must more than 5 digits';

			if (Base::$aRequest['data']['new_password']!=Base::$aRequest['data']['retype_new_password'] && !$sError)
			$sError='Passwords are not the same';

			if (!$sError) {
				$sSalt=String::GenerateSalt();
				$aUserUpdate=array(
				'password'=>String::Md5Salt(Base::$aRequest['data']['new_password'],$sSalt),
				'salt'=>$sSalt,
				);
				Db::AutoExecute('user',$aUserUpdate,"UPDATE"," id='".$aUser['id']."'");
				$sError=Language::GetMessage('You have successfully changed your password. Letter is sent to your email');
				$aUser['new_password']=Base::$aRequest['data']['new_password'];

				$aData=array('aUser'=>$aUser);
				$aTemplate=String::GetSmartyTemplate('new_password_letter', $aData);
				$sBody=$aTemplate['parsed_text'];

				Mail::AddDelayed($aUser['email'],$aTemplate['name'],$sBody,'','',true,$aTemplate['priority']);

				Base::$sText.=Language::GetText("new password changed successfully");
				return;
			}
		}

		$aField['new_password']=array('title'=>'New password','type'=>'password','value'=>Base::$aRequest['data']['new_password'],'name'=>'data[new_password]');
		$aField['retype_new_password']=array('title'=>'Retype new password','type'=>'password','value'=>Base::$aRequest['data']['retype_new_password'],'name'=>'data[retype_new_password]');
		$aField['id']=array('type'=>'hidden','name'=>'id','value'=>Base::$aRequest['id']);
		$aField['restore_code']=array('type'=>'hidden','name'=>'restore_code','value'=>Base::$aRequest['restore_code']);
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"New password Form",
		'sWidth'=>"350px",
		//'sContent'=>Base::$tpl->fetch('user/form_new_password.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Set new password',
		'sSubmitAction'=>'user_new_password',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
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
	//-------------------------------------------------------------------------------------------------
	public function ChangeLevelPrice()
	{
		$sUrl = '/';
		if (Base::$aRequest['uri'])
			$sUrl = Base::$aRequest['uri'];
		
		$sMessage="?aMessage[MI_NOTICE]=Level price updated";
		
		if (Base::$aRequest['type_price']) {
			Db::Execute("Update user_manager set type_price='".Base::$aRequest['type_price']."' where id_user=".Auth::$aUser['id_user']);
			// check user
			if (Base::$aRequest['type_price'] == 'user' && Base::$aRequest['data']['id_type_price_user']) {
				$aCustomer=Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['data']['id_type_price_user'])));
				if (!$aCustomer)
					$aMessage = "?aMessage[MI_NOTICE]=Incorrect selected user";
				else 
					Db::Execute("Update user_manager set id_type_price_user = ".$aCustomer['id_user']." where id_user=".Auth::$aUser['id_user']);
			}	
			elseif (Base::$aRequest['type_price'] == 'group' && Base::$aRequest['data']['id_type_price_group']) {
				$aGroup=Db::GetRow(Base::GetSql('CustomerGroup',array('id'=>Base::$aRequest['data']['id_type_price_group'])));
				if (!$aGroup || $aGroup['visible'] == 0)
					$aMessage = "?aMessage[MI_NOTICE]=Incorrect selected group";
				else 
					Db::Execute("Update user_manager set id_type_price_group = ".$aGroup['id']." where id_user=".Auth::$aUser['id_user']);
			}
			Auth::IsAuth(); // rescan user
			$this->RecalcCart(Auth::$aUser['id_user']);
		}
		else 
			Base::Redirect($sUrl);

		if($sUrl=="/") $sUrl="/pages/home";
		
		Base::Redirect($sUrl.$sMessage);
	}
	//-------------------------------------------------------------------------------------------------
	// $iIdUserNotManager - 1 use if manager create order for selected user
	public function RecalcCart($iIdUser,$iIdUserNotManager = 0) {
		if ($iIdUserNotManager){
			$aCart = Db::GetAll("Select * from cart where id_user=".Auth::$aUser['id_user']." and type_='cart'");
			$aUser = Base::$db->GetRow( Base::GetSql('Customer',array('id'=>$iIdUser)));
		}
		else {
			$aCart = Db::GetAll("Select * from cart where id_user=".$iIdUser." and type_='cart'");
			$aUser = Auth::$aUser; 
		}
		if ($aCart) {
			$aItemCode = array();
			foreach($aCart as $aValue) {
				$aItemCode[] = $aValue['item_code'];
			}
			$aPrice = array();
			if (count($aItemCode) > 0) 
				$aPrice=Db::GetAll($s=Base::GetSql('Catalog/Price', array(
						'aItemCode'=>$aItemCode,
						/*'id_provider' => $aValue['id_provider'],*/
						'customer_discount'=>Discount::CustomerDiscount($aUser),
						'not_change_recalc'=>$iIdUserNotManager
				)));
	        foreach($aCart as $aValue) {
				$iFound=0;
				foreach($aPrice as $aPriceValue) {
					if ($aPriceValue['item_code'] == $aValue['item_code'] && $aPriceValue['id_provider'] == $aValue['id_provider']) {
					    if ($aPriceValue['price'] == $aValue['price']) {
					        $iFound=1;
					        break;
					    }
						elseif ($aPriceValue['price'] != $aValue['price'] && $aPriceValue['price'] > 0) {
						    $dPrice = doubleval(Currency::PrintPrice($aPriceValue['price'],1,2,'<none>'));
						    if ($dPrice > 0) {
						        $iFound = 1;
							    Db::Execute("Update cart set price = '".$dPrice."' where id = ".$aValue['id']);
							    break;
						    }
						}
					}
				}
				if ($iFound == 0)
					Db::Execute("Delete from cart where id=".$aValue['id']);
			}
		}
	}
}
?>