<?

/**
 * @author Mikhail Starovoyt
 *
 */

class Customer extends Base
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		Auth::NeedAuth('customer');
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->Profile();
	}
	//-----------------------------------------------------------------------------------------------
	public function Profile()
	{
		Base::$bXajaxPresent=true;
		Base::$aTopPageTemplate=array('panel/tab_customer.tpl'=>'profile');

		if (Base::$aRequest['is_post']) {

			$sQuery="update user_customer set
				remark='".strip_tags(Base::$aRequest['data']['remark'])."',
				vip_remark='".strip_tags(Base::$aRequest['data']['vip_remark'])."'
			where id_user='".Auth::$aUser['id']."';";
			Base::$db->Execute($sQuery);

			$aUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
			'name','country','city','address','address2','zip','phone','phone2','remark'
			,'additional_field5','additional_field2','additional_field3','additional_field4'
			,'id_user_customer_type','entity_type','entity_name','additional_field1','id_currency'
			));
			Db::Autoexecute('user_customer',$aUserCustomer,'UPDATE',"id_user='".Auth::$aUser['id']."'");

			$aUser=String::FilterRequestData(Base::$aRequest['data'],array('email'));
			if (!String::CheckEmail($aUser['email'])) {
				$aUser['email']='';
				$sError.=Language::GetMessage("Not valid email and will be set to empty.");
			}
			Base::$db->Autoexecute('user',$aUser,'UPDATE',"id='".Auth::$aUser['id']."'");
			Auth::$aUser=Auth::IsUser(Auth::$aUser['login'],Auth::$aUser['password']);
			Base::Message(array('MF_NOTICE' => Language::getMessage('profile saved')));

			if (Auth::$aUser['has_forum']){
				Forum::ChangeProfile(Auth::$aUser);
			}
		}

		Auth::RefreshSession(Auth::$aUser);
		Auth::$aUser['amount_currency']=Base::$oCurrency->PrintPrice(Auth::$aUser['amount'],Auth::$aUser['id_currency']);

		Base::$tpl->assign('aUser',Auth::$aUser);
		Base::$tpl->assign('sManagerLogin',Base::$db->getOne("select login from user where id='".Auth::$aUser['id_manager']."'"));
		Base::$tpl->assign('sManagerName',
		Base::$db->getOne("select name from user_manager where id_user='".Auth::$aUser['id_manager']."'"));

		Base::$tpl->assign('aCurrency',$aCurrency);
        $aCurrency=Base::$db->getAll("select * from currency where visible=1 order by num");
        $aCurrency=Db::GetAssoc("select id, name from currency where visible=1 order by num");
		
		Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
		    '1'=>Language::GetMessage('частное лицо'),
		    '2'=>Language::GetMessage('юридическое лицо')
		));
		$aEntityType=explode(",",Language::GetConstant('user:entity_type', 'ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
		Base::$tpl->assign('aEntityType',$aEntityType);

		$aData=array(
		'table'=>'rating',
		'where'=>" and section='store_customer' and num in (1,2,4)",
		//'locale_where'=>" and l.visible='1' ",
		'order'=>" order by t.num",
		);
		$aTmp=Language::GetLocalizedAll($aData);
		foreach ($aTmp as $aValue) {
			$aRating[$aValue['num']]=$aValue['content']?$aValue['content']:$aValue['name'];
		}
		Base::$tpl->assign('aRatingAssoc',$aRating);
		
		foreach ($aEntityType as $aValue){
		    $aEntityTypeOptions[$aValue]=$aValue;
		}
		
		Resource::Get()->Add('/js/user.js',2);
		
		$aField['login']=array('title'=>'Your login','type'=>'text','value'=>Auth::$aUser['login']);
		if($bLoginChange) {
		    $aField['login']['contexthint']='customer_account_login_change';
		    $aField['login']['add_to_td']=array('change_login_link'=>array('type'=>'link','href'=>'/?action=user_change_login','caption'=>Language::GetMessage('Change Login')));
		}
		$aField['email']=array('title'=>'Your email','type'=>'input','value'=>Auth::$aUser['email'],'name'=>'data[email]');
		$aField['passsword']=array('title'=>'Password','type'=>'text','value'=>'******');
		if (!$bReadOnly) $aField['passsword']['add_to_td']=array('change_password'=>array('type'=>'link','href'=>'/?action=user_change_password','caption'=>Language::GetMessage('Change Password')));
		$aField['manager_login']=array('title'=>'Your manager','type'=>'text','value'=>Auth::$aUser['manager_login'].'&nbsp;','add_to_td'=>array(
		    'message_to_manager'=>array('type'=>'link','href'=>'/?action=message_compose&compose_to='.Auth::$aUser['manager_login'],'caption'=>Language::GetMessage('Send message to manager'))
		));
		$aField['messages']=array('title'=>'Your messages','type'=>'link','href'=>'/?action=message','caption'=>Language::GetMessage('Look for messages'));
		if($iUnreadMessages > 0){
		    $aField['messages']['add_to_td']=array('unread_messages'=>array('type'=>'text','value'=>'('.Language::GetMessage('You have').' '.$iUnreadMessages.' '.Language::GetMessage('unread messages').')'));
		}
		$aField['discount_static']=array('title'=>'Discount Static','type'=>'text','value'=>Auth::$aUser['discount_static'].' %','contexthint'=>'customer_discount_static');
		$aField['discount_dynamic']=array('title'=>'Discount Dynamic','type'=>'text','value'=>Auth::$aUser['discount_dynamic'].' %','contexthint'=>'customer_discount_dynamic');
		if(Auth::$aUser['cg_visible']!=1)Auth::$aUser['group_discount']=0;
		$aField['group_discount']=array('title'=>'Group Discount','type'=>'text','value'=>Auth::$aUser['group_discount'].' %','contexthint'=>'customer_group_discount');
// 		$aField['id_currency']=array('title'=>'Basic Currency','type'=>'select','options'=>$aCurrency,'selected'=>Auth::$aUser['id_currency'],'name'=>'data[id_currency]','contexthint'=>'customer_basic_currency');
		$aField['delivery_info']=array('type'=>'text','value'=>Language::GetMessage("Delivery info"),'colspan'=>2);
		$aField['hr']=array('type'=>'hr','colspan'=>2);
		$aField['id_user_customer_type']=array('title'=>'User customer type','type'=>'select','options'=>$aUserCustomerType,'selected'=>(Auth::$aUser['id_user_customer_type']!='')?Auth::$aUser['id_user_customer_type']:Base::$aRequest['data']['id_user_customer_type'],
		    'name'=>'data[id_user_customer_type]','onchange'=>"oUser.ToggleEntityTr($('#user_customer_type_id').val())",'id'=>'user_customer_type_id');
		$aField['entity_type']=array('title'=>'Entity name','type'=>'select','options'=>$aEntityTypeOptions,'selected'=>(Auth::$aUser['entity_type']!='')?Auth::$aUser['entity_type']:Base::$aRequest['data']['entity_type'],'name'=>'data[entity_type]','tr_id'=>'entity_tr_id','add_to_td'=>array(
		    'entity_name'=>array('type'=>'input','value'=>Auth::$aUser['entity_name']?Auth::$aUser['entity_name']:Base::$aRequest['data']['entity_name'],'name'=>'data[entity_name]')
		));
		$aField['additional_field1']=array('title'=>'additional_field1','type'=>'input','value'=>Auth::$aUser['additional_field1']?Auth::$aUser['additional_field1']:Base::$aRequest['data']['additional_field1'],'name'=>'data[additional_field1]','tr_id'=>'additional_field1_tr_id');
		$aField['additional_field2']=array('title'=>'additional_field2','type'=>'input','value'=>Auth::$aUser['additional_field2']?Auth::$aUser['additional_field2']:Base::$aRequest['data']['additional_field2'],'name'=>'data[additional_field2]','tr_id'=>'additional_field2_tr_id');
		$aField['additional_field3']=array('title'=>'additional_field3','type'=>'input','value'=>Auth::$aUser['additional_field3']?Auth::$aUser['additional_field3']:Base::$aRequest['data']['additional_field3'],'name'=>'data[additional_field3]','tr_id'=>'additional_field3_tr_id');
		$aField['additional_field4']=array('title'=>'additional_field4','type'=>'input','value'=>Auth::$aUser['additional_field4']?Auth::$aUser['additional_field4']:Base::$aRequest['data']['additional_field4'],'name'=>'data[additional_field4]','tr_id'=>'additional_field4_tr_id');
		$aField['additional_field5']=array('title'=>'additional_field5','type'=>'input','value'=>Auth::$aUser['additional_field5']?Auth::$aUser['additional_field5']:Base::$aRequest['data']['additional_field5'],'name'=>'data[additional_field5]','tr_id'=>'additional_field5_tr_id');
		$aField['name']=array('title'=>'FLName','type'=>'input','value'=>Auth::$aUser['name'],'name'=>'data[name]','szir'=>1);
		$aField['city']=array('title'=>'City','type'=>'input','value'=>Auth::$aUser['city'],'name'=>'data[city]','szir'=>1);
		$aField['address']=array('title'=>'Address','type'=>'input','value'=>Auth::$aUser['address'],'name'=>'data[address]','szir'=>1);
		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Auth::$aUser['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
		$aField['store_num_rating']=array('title'=>'Store num rating','type'=>'select','options'=>$aRating,'selected'=>Auth::$aUser['num_rating'],'name'=>'data[num_rating]','onchange'=>"xajax_process_browse_url('/?action=customer_change_rating&id_user=".$aRow['id_user']."&num_rating='+this.value); return false;");
		$aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>Auth::$aUser['remark']); 
		if($bReadOnly){
		    $aField['email']['readonly']=1;
		    $aField['name']['readonly']=1;
		    $aField['id_currency']['disabled']=1;
		    $aField['city']['readonly']=1;
		    $aField['address']['readonly']=1;
		    $aField['phone']['readonly']=1;
		    $aField['remark']['disabled']=1;
		} 
		if(Auth::$aUser['id_user_customer_type']!=''){
		    if(Auth::$aUser['id_user_customer_type']==1)
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
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Customer Profile",
// 		'sContent'=>Base::$tpl->fetch('customer/profile.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Apply',
		'sSubmitAction'=>'customer_profile',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();

		//Base::$tpl->assign('sForm',);
		//Base::$sText.=Base::$tpl->fetch('user/outer_profile.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function IsChangeableLogin($sLogin) {
		return preg_match("/^[a-zA-Z]{1}[0-9]*$/", $sLogin);
	}
	//-----------------------------------------------------------------------------------------------
	public function IsTempUser($sLogin='') {
		if(!$sLogin) $sLogin=Auth::$aUser['login'];
		$bTempUser=Base::$db->getOne("select is_temp from user where login='".$sLogin."'");
		return $bTempUser;
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeRating()
	{
		if (Base::$aRequest['num_rating']) {
			Rating::Change('store_customer',Auth::$aUser['id'],Base::$aRequest['num_rating']);
		}
	}
	//-----------------------------------------------------------------------------------------------

}
?>