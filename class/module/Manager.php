<?

/**
 * @author Mikhail Starovoyt
 * @author Oleksandr Starovoit
 */

class Manager extends Base
{
	var $sPrefix="manager";
	var $sPrefixAction="";
	private $sCustomerSql;
	public $sExportSql;
	public $sExportMegaSql;

	public $aCustomerList;
	public $sCurrentOrderStatus;

	//public $aUserManagerRegionId=array();

	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		if (Base::$aRequest['action']=='cart_create' && Auth::$aUser['type_']=='manager') {}
		else
			Auth::NeedAuth('manager');
		
		Base::$aData['template']['bWidthLimit']=false;

		if (Auth::$aUser['is_super_manager'] || Auth::$aUser['is_sub_manager'])
		$this->sCustomerSql="SELECT id_user from user_customer";
		else $this->sCustomerSql="SELECT id_user from user_customer where id_manager='".Auth::$aUser['id']."'";

		Base::$bXajaxPresent=true;
		Base::$aData['template']['bWidthLimit']=false;

		Base::$tpl->assign_by_ref("oManager", $this);
		
		//$this->aUserManagerRegionId=array_keys(Db::GetAssoc('Assoc/UserManagerRegion',array('id_user'=>Auth::$aUser['id'])));
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->Profile();
		
	}
	//-----------------------------------------------------------------------------------------------
	public function GetCustomerList()
	{
		if ($this->aCustomerList) return $this->aCustomerList;

		$this->aCustomerList=Base::$db->getAll("select u.*, uc.* , cg.name as customer_group_name
			from user u
			inner join user_customer uc on u.id=uc.id_user
			inner join user_account ua on u.id=ua.id_user
			inner join customer_group cg on cg.id=uc.id_customer_group
			where 1=1
			 	and u.id in (".$this->sCustomerSql.")
			group by u.id ");
		return $this->aCustomerList;
	}
	//-----------------------------------------------------------------------------------------------
	public function Profile()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'profile');

		if (Base::$aRequest['is_post']) {
			$sQuery="
			update user_manager set
				name='".Base::$aRequest['name']."',
				country='".Base::$aRequest['country']."',
				state='".Base::$aRequest['state']."',
				city='".Base::$aRequest['city']."',
				zip='".Base::$aRequest['zip']."',
				company='".Base::$aRequest['company']."',
				address='".Base::$aRequest['address']."',
				phone='".Base::$aRequest['phone']."',
				remark='".Base::$aRequest['remark']."'
			where id_user='".Auth::$aUser['id']."';
			";
			Base::$db->Execute($sQuery);
			Base::Message(array('MF_NOTICE' => Language::getMessage('profile saved')));

			Auth::$aUser=Auth::IsUser(Auth::$aUser['login'],Auth::$aUser['password']);
			if (Auth::$aUser['has_forum']){
				Forum::ChangeProfile(Auth::$aUser);
			}
		}

		Auth::RefreshSession(Auth::$aUser);
		Base::$tpl->assign('aUser',Auth::$aUser);
		//$aCurrency=Base::$db->getAll("select * from currency where visible=1 order by num");
		//Base::$tpl->assign('aCurrency',$aCurrency);

		require_once(SERVER_PATH.'/class/core/Form.php');
		
		$aField['name']=array('title'=>'Name','type'=>'input','value'=>Auth::$aUser['name'],'name'=>'name');
		$aField['password']=array('title'=>'Password','type'=>'text','value'=>'******','add_to_td'=>array(
		    'user_change_password'=>array('type'=>'link','href'=>'/?action=user_change_password','caption'=>Language::GetMessage('Change Password'))
		));
		$aField['country']=array('title'=>'Country','type'=>'input','value'=>Auth::$aUser['country'],'name'=>'country');
		$aField['state']=array('title'=>'State','type'=>'input','value'=>Auth::$aUser['state'],'name'=>'state');
		$aField['city']=array('title'=>'City','type'=>'input','value'=>Auth::$aUser['city'],'name'=>'city');
		$aField['address']=array('title'=>'Address','type'=>'textarea','name'=>'address','value'=>Auth::$aUser['address']);
		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Auth::$aUser['phone'],'name'=>'phone');
		$aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'remark','value'=>Auth::$aUser['remark']);
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Manager Profile",
		//'sContent'=>Base::$tpl->fetch('manager/profile.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Apply',
		'sSubmitAction'=>'manager_profile',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();
		Base::$oContent->AddCrumb(Language::GetMessage('Profile'));
	}
	//-----------------------------------------------------------------------------------------------
	public function Customer()
	{
		Base::$sText.=Base::$tpl->fetch('manager/link_calculation.tpl');
		
	    if(Auth::$aUser['is_super_manager'])
	        $sWhereManager = ' ';
	    else
	        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'customer');
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['name']=array('title'=>'CustName','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Base::$aRequest['search']['phone'],'name'=>'search[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __');
		$aData=array(
		'sHeader'=>"method=get",
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_customer',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();

		// --- search ---
		//if (Base::$aRequest['search']['login']) $sWhere.=" and u.login like '%".Base::$aRequest['search']['login']."%'";
		if (Base::$aRequest['search_login']) {
		    $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		
		if (Base::$aRequest['search']['name']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['name']."%'";
		if (Base::$aRequest['search']['phone']) $sWhere.=" and uc.phone like '%".Base::$aRequest['search']['phone']."%'";
		// --------------

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->sSql="select cg.*,uc.*, ua.* ,u.*, cg.name as group_name
					, m.login as manager_login
					 from user u
				inner join user_customer uc on uc.id_user=u.id
				inner join user_account ua on ua.id_user=u.id
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user m on uc.id_manager=m.id
			 where 1=1
			 	and u.id in (".$this->sCustomerSql.") and u.visible=1 
			 ".$sWhere;
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'CustID','sWidth'=>'15px'),
		'login'=>array('sTitle'=>'Login'),
// 		'name'=>array('sTitle'=>'CustName/Phone'),
		'group_name'=>array('sTitle'=>'Group Name'),
		'email'=>array('sTitle'=>'Email','sWidth'=>'20%'),
		'amount'=>array('sTitle'=>'CustAmount','sWidth'=>'20%','sOrder'=>'amount'),
		'action'=>array(),
		);
		$oTable->aOrdered="order by uc.name";
		$oTable->iRowPerPage=20;
		$oTable->sDataTemplate='manager/row_customer.tpl';
		$oTable->aCallback=array($this,'CallParseOrder');

		$iSumBalance=Db::GetOne("
		    select sum(ua.amount)
		    from user_account as ua
		    inner join user_customer as uc on uc.id_user=ua.id_user
		");
		Base::$tpl->assign('iSumBalance',$iSumBalance);
		Base::$sText.=$oTable->getTable("My customers");
	}
	//-----------------------------------------------------------------------------------------------
	public function CustomerEdit(){
	    $iIDUser=(int)abs(Base::$aRequest['id']);
	    $sReturn= trim(strip_tags(Base::$aRequest['return'])) ? trim(strip_tags(Base::$aRequest['return'])) : 'manager_customer';
	    $sReturn = str_replace('action=','',$sReturn);
	    if (Base::$aRequest['is_post']) {
	        Db::Execute("update user set approved='".Base::$aRequest['data']['approved']."' where id=".$iIDUser);
	        Rating::Change('store_customer',$iIDUser,Base::$aRequest['data']['num_rating']);
	        $aUserCustomer=String::FilterRequestData(Base::$aRequest['data'],array(
	            'city','id_currency','name','discount_dynamic','discount_static','phone'
	            ,'address','id_user_customer_type','id_user_customer_type1','remark',
	            'name','country','city','address','address2','zip','phone','phone2','remark'
    			,'additional_field5','additional_field2','additional_field3','additional_field4'
    			,'id_user_customer_type','entity_type','entity_name','additional_field1','id_customer_group'
	        ));
	        Db::Autoexecute('user_customer',$aUserCustomer,'UPDATE',"id_user='".$iIDUser."'");
	        Base::Redirect($_SERVER['REQUEST_URI']);
	    }
	
	    $aUser = Db::GetRow("select * from user_customer where id_user=".$iIDUser);
	    $aUserInf = Db::GetRow("select login, email, approved from user where id=".$iIDUser);
	    $aUser['login'] = $aUserInf['login'];
	    $aUser['email'] = $aUserInf['email'];
	    $aUser['approved'] = $aUserInf['approved'];
	    $aUser['group_discount'] = DB::GetOne("select group_discount from customer_group where visible=1 and id=".$aUser['id_customer_group']);
	    $iIdManager = DB::GetOne("Select id_manager from user_customer where id_user=".$iIDUser);
	    $aUser['manager_login'] = Db::GetOne("select login from user where id='".$iIdManager."'");
	    Base::$tpl->assign('aUser',$aUser);
	    //Base::$tpl->assign('aCurrency',$aCurrency=Base::$db->getAll("select * from currency where visible=1 order by num"));
	    $aCurrency=Db::GetAssoc("select id, name from currency where visible=1 order by num");
	    if (Auth::$aUser['id'] != $iIdManager) Base::$tpl->assign('bReadOnly', $bReadOnly=1);
   
	    $aData=array(
	        'table'=>'rating',
	        'where'=>" and section='store_customer' and num in (1,2,4)",
	        'order'=>" order by t.num",
	    );
	    $aTmp=Language::GetLocalizedAll($aData);
	    foreach ($aTmp as $aValue) {
	        $aRating[$aValue['num']]=$aValue['content']?$aValue['content']:$aValue['name'];
	    }
	    Base::$tpl->assign('aRatingAssoc',$aRating);
	    Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
	        '1'=>Language::GetMessage('частное лицо'),
	        '2'=>Language::GetMessage('юридическое лицо')
	    ));
	    $aEntityType=explode(",",Language::GetConstant('user:entity_type', 'ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
	    Base::$tpl->assign('aEntityType',$aEntityType);

	    foreach ($aEntityType as $aValue){
	        $aEntityTypeOptions[$aValue]=$aValue;
	    }
	    
	    $aCustomerGroupAssoc=DB::GetAssoc('Assoc/CustomerGroup',array(
	    'where' => ' and cg.visible=1'));
	    Base::$tpl->assign('aCustomerGroupAssoc',$aCustomerGroupAssoc);
	    
// 	    Debug::PrintPre($aUser,false); 
	    
	    Resource::Get()->Add('/js/user.js',2);
	    $bLoginChange=Base::$tpl->get_template_vars('bLoginChange');
	    $aField['login']=array('title'=>'Login','type'=>'text','value'=>$aUser['login']);  
	    if($bLoginChange) $aField['login']['contexthint']='customer_account_login_change';
	    $aField['email']=array('title'=>'Your email','type'=>'input','value'=>$aUser['email'],'name'=>'data[email]','readonly'=>1);
	    $aField['passsword']=array('title'=>'Password','type'=>'text','value'=>'******');
	    $aField['manager_login']=array('title'=>'Your manager','type'=>'text','value'=>$aUser['manager_login']);
	    $aField['id_customer_group']=array('title'=>'Сustomer group','type'=>'select','options'=>$aCustomerGroupAssoc,'selected'=>$aUser['id_customer_group'],'name'=>'data[id_customer_group]');
	    $aField['discount_static']=array('title'=>'Discount Static','type'=>'input','value'=>$aUser['discount_static'],'name'=>'data[discount_static]','contexthint'=>'customer_discount_static');
	    $aField['discount_dynamic']=array('title'=>'Discount Dynamic','type'=>'input','value'=>$aUser['discount_dynamic'],'name'=>'data[discount_dynamic]','contexthint'=>'customer_discount_dynamic');
	    $aField['group_discount']=array('title'=>'Group Discount','type'=>'text','value'=>$aUser['group_discount'].' %','contexthint'=>'customer_group_discount');
	    $aField['id_currency']=array('title'=>'Basic Currency','type'=>'select','options'=>$aCurrency,'selected'=>$aUser['id_currency'],'name'=>'data[id_currency]','contexthint'=>'customer_basic_currency');
	    $aField['delivery_info']=array('type'=>'text','value'=>Language::GetMessage("Delivery info"),'colspan'=>2);
	    $aField['hr']=array('type'=>'hr','colspan'=>2);
	    $aField['id_user_customer_type']=array('title'=>'User customer type','type'=>'select','options'=>$aUserCustomerType,'selected'=>($aUser['id_user_customer_type']!='')?$aUser['id_user_customer_type']:Base::$aRequest['data']['id_user_customer_type'],
	        'name'=>'data[id_user_customer_type]','onchange'=>"oUser.ToggleEntityTr($('#user_customer_type_id').val())",'id'=>'user_customer_type_id');
	    $aField['entity_type']=array('title'=>'Entity name','type'=>'select','options'=>$aEntityTypeOptions,'selected'=>($aUser['entity_type']!='')?$aUser['entity_type']:Base::$aRequest['data']['entity_type'],'name'=>'data[entity_type]','tr_id'=>'entity_tr_id','add_to_td'=>array(
	        'entity_name'=>array('type'=>'input','value'=>$aUser['entity_name']?$aUser['entity_name']:Base::$aRequest['data']['entity_name'],'name'=>'data[entity_name]','tr_class'=>'entity_tr_id')
	    ));
	    $aField['additional_field1']=array('title'=>'additional_field1','type'=>'input','value'=>$aUser['additional_field1']?$aUser['additional_field1']:Base::$aRequest['data']['additional_field1'],'name'=>'data[additional_field1]','tr_id'=>'additional_field1_tr_id');
	    $aField['additional_field2']=array('title'=>'additional_field2','type'=>'input','value'=>$aUser['additional_field2']?$aUser['additional_field2']:Base::$aRequest['data']['additional_field2'],'name'=>'data[additional_field2]','tr_id'=>'additional_field2_tr_id');
	    $aField['additional_field3']=array('title'=>'additional_field3','type'=>'input','value'=>$aUser['additional_field3']?$aUser['additional_field3']:Base::$aRequest['data']['additional_field3'],'name'=>'data[additional_field3]','tr_id'=>'additional_field3_tr_id');
	    $aField['additional_field4']=array('title'=>'additional_field4','type'=>'input','value'=>$aUser['additional_field4']?$aUser['additional_field4']:Base::$aRequest['data']['additional_field4'],'name'=>'data[additional_field4]','tr_id'=>'additional_field4_tr_id');
	    $aField['additional_field5']=array('title'=>'additional_field5','type'=>'input','value'=>$aUser['additional_field5']?$aUser['additional_field5']:Base::$aRequest['data']['additional_field5'],'name'=>'data[additional_field5]','tr_id'=>'additional_field5_tr_id'); 
	    $aField['name']=array('title'=>'FLName','type'=>'input','value'=>$aUser['name'],'name'=>'data[name]','szir'=>1);
	    $aField['city']=array('title'=>'City','type'=>'input','value'=>$aUser['city'],'name'=>'data[city]','szir'=>1);
	    $aField['address']=array('title'=>'Address','type'=>'input','value'=>$aUser['address'],'name'=>'data[address]','szir'=>1);
	    $aField['phone']=array('title'=>'Phone','type'=>'input','value'=>$aUser['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
	    $aField['store_num_rating']=array('title'=>'Store num rating','type'=>'select','options'=>$aRating,'selected'=>$aUser['num_rating'],'name'=>'data[num_rating]');
	    $aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>$aUser['remark']);
	    $aField['approved']=array('title'=>'Approved','type'=>'checkbox','name'=>'data[approved]','value'=>'1','checked'=>$aUser['approved']);
	    
	    if($bReadOnly){
	        $aField['discount_static']['readonly']=1;
	        $aField['discount_dynamic']['readonly']=1;
	        $aField['id_currency']['disabled']=1;
	        $aField['name']['readonly']=1;
	        $aField['city']['readonly']=1;
	        $aField['address']['readonly']=1;
	        $aField['phone']['readonly']=1;
	        $aField['store_num_rating']['disabled']=1;
	        $aField['remark']['disabled']=1;
	        $aField['approved']['disabled']=1;
	    }
	     if($aUser['id_user_customer_type']!=''){
	        if($aUser['id_user_customer_type']==1)
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
	        'sTitle'=>"Customer Profile Edit",
	        //'sContent'=>Base::$tpl->fetch('manager/form_customer_edit.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sSubmitButton'=>'Apply',
	        'sSubmitAction'=>'manager_customer_edit',
	        'sReturnButton'=>'return',
	        'sReturnAction'=>$sReturn,
	        'sError'=>$sError,
	        'sWidth'=>'45%'
	    );
	    $oForm=new Form($aData);
	    Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function CustomerRedirect()
	{
		//$id_array=Base::db->GetAll
		if (Base::$db->getOne("select count(*) from user where id='".Base::$aRequest['id']."'
			and id in (".$this->sCustomerSql.")")) {

		$aManager=Base::$db->getRow("select u.*,um.* from user_manager um,user u
				where u.id=um.id_user and u.visible=1 and um.has_customer=1
					and u.id!='".Base::$aRequest['id_manager']."' order by rand()
				");
		if ($aManager) {
			Base::$db->Execute("update user_customer set id_manager='".$aManager['id']."'
					where id_user='".Base::$aRequest['id']."'");
		}
			}
			Base::Redirect("/?action=manager_customer");
	}
	//-----------------------------------------------------------------------------------------------
	public function Order()
	{
		if (Base::$aRequest['search']['id_cart_package']) {
			Base::$db->Execute("update cart_package set is_viewed='1' where id='".Base::$aRequest['search']['id_cart_package']."' ");
			$sPrintPac="/?action=manager_order_print&id=".Base::$aRequest['search']['id_cart_package']."&id_user=".Db::GetOne("SELECT id_user FROM `cart_package` where id=".Base::$aRequest['search']['id_cart_package']);
			$sPrintPac="<a class=btn style='padding: 5px;' href='".$sPrintPac."' target='_blank'>
			    <img src='/image/fileprint.png' border='0' width='16' align='absmiddle' hspace='1/'>Печать заказа</a>";
		}
		
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'order');
		Resource::Get()->Add('/css/manager_panel.css');

		if (Base::$aRequest['is_post']) {
			//[----- UPDATE -----------------------------------------------------]
			//if (Base::$aRequest['order_status']!=Base::$aRequest['old_order_status']) {
			$sMessage=$this->ProcessOrderStatus();
			//}
			Base::$db->Execute("update cart set
					weight='".Base::$aRequest['weight']."'
					 , id_provider_order='".Base::$aRequest['id_provider_order']."'
					 , id_provider_invoice='".Base::$aRequest['id_provider_invoice']."'
					 , provider_price='".Base::$aRequest['provider_price']."'
					 where id='".Base::$aRequest['id']."'
						and id_user in (".$this->sCustomerSql.") ");
			//[----- END UPDATE -------------------------------------------------]
			//Base::Redirect("/?action=manager_order");
			//if ($sMessage) $sAddedMessage.=;
			//Form::AfterReturn('manager_order'.$sAddedMessage,"&aMessage[MT_NOTICE]=".$sMessage);
			Base::Redirect("/".Base::$aRequest['return']."&aMessage[MT_NOTICE_NT]=".$sMessage);
		}

		if ( Base::$aRequest['action']=='manager_order_edit') {
			//closed change status only for super manager
			if (!Auth::$aUser['is_super_manager']) Base::Redirect('/?action=auth_type_error');//Base::Redirect('/?closed');

			Form::BeforeReturn('manager_order','manager_order_edit');

			$aCart=Base::$db->getRow("
				select cg.*,u.*,uc.*,c.*,u.login, uc.name as customer_name
				from cart c
				inner join user u on c.id_user=u.id
				inner join user_customer uc on uc.id_user=u.id
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user_account ua on ua.id_user=u.id

				where 1=1 and c.type_='order'
					and c.id='".Base::$aRequest['id']."'
					and c.id_user in (".$this->sCustomerSql.") order by c.id");
			if (!$aCart) Base::Redirect('/?action=manager_order');

			Base::$tpl->assign('aData',$aCart);
			include(SERVER_PATH.'/include/order_status_config.php');
			Base::$tpl->assign('aOrderStatusConfig',$aOrderStatusConfig[$aCart['order_status']]);
			Base::$tpl->assign('aUnstateOrderStatus',$aUnstateOrderStatus);
			Base::$tpl->assign("aPrefChange",$aPrefChange=Db::GetAssoc("Assoc/Pref", array("is_price"=>1)));

			foreach ($aOrderStatusConfig[$aCart['order_status']] as $item){
			    $aOrderStatus[$item]=Language::GetMessage($item);
			}
			foreach ($aUnstateOrderStatus as $item){
			    $aOrderStatus[$item]=Language::GetMessage($item);
			}
			
			$aField['old_order_status']=array('title'=>'Old Order Status','type'=>'text','value'=>Language::GetMessage($aCart['order_status']));
			$aField['old_order_status_hidden']=array('type'=>'hidden','name'=>'old_order_status','value'=>$aCart['order_status']);
			$aField['new_order_status']=array('title'=>'New Order Status','type'=>'select','options'=>$aOrderStatus,'name'=>order_status);
			$aField['pref_changed']=array('title'=>'New Pref (If status change_code)','type'=>'select','options'=>$aPrefChange,'selected'=>$aCart['pref_changed']?$aCart['pref_changed']:$aCart['pref'],'name'=>'pref_changed');
			$aField['comment']=array('title'=>'Comment (If status changes)','type'=>'textarea','name'=>'comment','value'=>$aCart['comment']);
			$aField['weight']=array('title'=>'Weight (kg)','type'=>'input','value'=>$aCart['weight'],'name'=>'weight');
			$aField['id_provider_order']=array('title'=>'Id Provider Order','type'=>'input','value'=>$aCart['id_provider_order'],'name'=>'id_provider_order');
			$aField['provider_price']=array('title'=>'Provider Price','type'=>'input','value'=>$aCart['provider_price'],'name'=>'provider_price');
			$aField['id_provider_invoice']=array('title'=>'Id Provider Invoice','type'=>'input','value'=>$aCart['id_provider_invoice'],'name'=>'id_provider_invoice');
			$aField['custom_value']=array('title'=>'Custom Value','type'=>'input','value'=>$aCart['custom_value'],'name'=>'custom_value');
			$aField['ignore_confirm_growth']=array('title'=>'Ignore confirm price growth','type'=>'checkbox','value'=>1,'name'=>'ignore_confirm_growth','checked'=>1);
			
			$aData=array(
			'sHeader'=>"method=post",
 			//'sContent'=>Base::$tpl->fetch('manager/form_order.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_order',
			'sReturnButton'=>'<< Return',
			//'sReturnAction'=>'manager_order',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);
			$oForm->bAutoReturn=true;
			Base::$sText.=$oForm->getForm();
			return;
		}

		$sSql=Base::GetSql('UserProvider');
		Base::$tpl->assign('aProvider',Base::$db->getAll($sSql));
		$a=array(""=>"all");
		Base::$tpl->assign('aUserManager',array(""=>"")+Base::$db->GetAssoc("select id, login as name
			from user where type_='manager' and visible=1"));
// 		$aPref=Db::GetAssoc("Assoc/Pref");

// 		include(SERVER_PATH.'/include/order_status_config.php');
		$aAllOrderStatus=array('notend','new','work','confirmed', 'road', 'store', 'end', 'refused');
// 		$aOrderStatus[0]=Language::GetMessage("notend");
		foreach ($aAllOrderStatus as $item){
		    $aOrderStatus[$item]=Language::GetMessage($item);
		}
		
		$aField=array();
		$aField['id']=array('title'=>'#','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['id_cart_package']=array('title'=>'cart package','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['brand']=array('title'=>'brand','type'=>'input','value'=>Base::$aRequest['search']['brand'],'name'=>'search[brand]');
		$aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
		$aField['name']=array('title'=>'name','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
		$aField['search_order_status']=array('title'=>'Order Status','type'=>'select','options'=>$aOrderStatus,'name'=>'search_order_status','selected'=>Base::$aRequest['search_order_status']);
		$aField['provider']=array('title'=>'provider','type'=>'input','value'=>Base::$aRequest['search']['provider'],'name'=>'search[provider]');
		$aField['uc_name']=array('title'=>'customer','type'=>'input','value'=>Base::$aRequest['search']['uc_name'],'name'=>'search[uc_name]');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("Y-m-1",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'yyyy-mm-dd')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("Y-m-d",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'yyyy-mm-dd')");
		
		$aData=array(
		'sHeader'=>"method=get",
// 		'sContent'=>Base::$tpl->fetch('manager/form_order_search.tpl'),
	    'aField'=>$aField,
	    'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_order',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->GetForm();

		if (!isset(Base::$aRequest['search_order_status']) || Base::$aRequest['search_order_status']=='notend') {
			$sWhere.=" and c.order_status not in ('end', 'refused','return_provider','return_store') ";
		} elseif (Base::$aRequest['search_order_status']!='0') {
			$sWhere.=" and c.order_status = '".Base::$aRequest['search_order_status']."'";
			if (Base::$aRequest['search_id_user_manager']) {
				$iSearchIdUserManager=Base::$aRequest['search_id_user_manager'];
			}
		}

		/*if (Base::$aRequest['search_date'] || Base::$aRequest['search_order_status']) {
			if (Base::$aRequest['search']['date_type']=='cart') {
				$sDateField='c';
				$bCartJoin=false;
			}
			else {
				$sDateField='cl';
				$bCartJoin=true;
			}

			$sWhere.=" and ".$sDateField.".post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ".$sDateField.".post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		}*/

		if (Base::$aRequest['search_order_status'] && Base::$aRequest['search_order_status']!='0')
			$bCartJoin=true;
		
		if (Base::$aRequest['search']['period']) {
			list($sDateFrom,$sDateTo)=explode("-",Base::$aRequest['search']['period']);
		}
		if (Base::$aRequest['search']['date']) {
		    $sDateFrom=DateFormat::FormatSearch(Base::$aRequest['search']['date_from'],"d.m.Y 00:00:00");
		    $sDateTo=DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"d.m.Y 23:59:59");
		    unset(Base::$aRequest['search']['date_from']);
		    unset(Base::$aRequest['search']['date_to']);
		} else {
		    unset(Base::$aRequest['search']['date_from']);
		    unset(Base::$aRequest['search']['date_to']);
		}
		if (Base::$aRequest['search']['brand']) {
		    $sWhere.=" and c.cat_name like '%".trim(Base::$aRequest['search']['brand'])."%'  ";
		}
		if (Base::$aRequest['search']['provider']) {
		    $sWhere.=" and up.name like '%".trim(Base::$aRequest['search']['provider'])."%'  ";
		}
		// --------------

		require(SERVER_PATH.'/include/order_status_config.php');
		Base::$tpl->assign('aOrderStatus',array_keys($aOrderStatusConfig));
		Base::$tpl->assign('aAllowChangeProviderDetailStatus',$aAllowChangeProviderDetailStatus);
		Base::Message();

		if (1 || count(Base::$aRequest['search'])>=1) {
			if (!Base::$aRequest['search']) Base::$aRequest['search']=array();
			
			if (Base::$aRequest['search']['id_provider']=='undefined')
				Base::$aRequest['search']['id_provider']='';
			
			$oTable=new Table();
			$oTable->sPanelTemplateTop='manager/panel.tpl';
			$oTable->sSql=Base::GetSql("Part/Search",Base::$aRequest['search']+array(
			"where"=>$sWhere,
			"id_user_manager"=>$iSearchIdUserManager,
			"cart_log_join"=>$bCartJoin,
			"is_confirm"=>1,
			"cp_date_from"=>trim($sDateFrom),
			"cp_date_to"=>trim($sDateTo),
			"is_buh_balance"=>1,
			"id_provider"=>Base::$aRequest['search']['id_provider'],
			));

			$_SESSION['order']['current_sql']=$oTable->sSql;
			$sSubtotalSql="select price, number from (select c.* ".substr($oTable->sSql,strpos($oTable->sSql,'from')).") t";
			$aMass = Base::$db->getAll($sSubtotalSql);
			$dOrderSubtotal = 0;
			foreach($aMass as $aValue)
				$dOrderSubtotal += $aValue['number'] * Currency::PrintPrice($aValue['price']);
			
			Base::$tpl->assign('dOrderSubtotal',$dOrderSubtotal);
			
			$oTable->aOrdered="order by /*c.post_date desc,*/ cp.post_date desc, cp.id desc ";
			$oTable->aCallbackAfter=array($this,'CallParseOrder');

			$oTable->aColumn=array(
			'id_cart_package'=>array('sTitle'=>'#CP'),
// 			'user'=>array('sTitle'=>'man_User'),
			'cat_name'=>array('sTitle'=>'man_Brand'),
// 			'code'=>array('sTitle'=>'CartCode'),
			'name'=>array('sTitle'=>'Name'),
			'provider'=>array('sTitle'=>'Provider'),
			'price'=>array('sTitle'=>'Price'),
			'total'=>array('sTitle'=>'Qty/Total'),
// 			'post_date'=>array('sTitle'=>'Date'),
// 			'order_status'=>array('sTitle'=>'man_Order Status'),
			'action'=>array(),
			);
			$oTable->iRowPerPage=50;
// 			$oTable->sSubtotalTemplateTop='manager/subtotal_order_top.tpl';
			$oTable->sDataTemplate='manager/row_order.tpl';
			$oTable->bCheckVisible=true;
			$oTable->bDefaultChecked=false;
// 			$oTable->sSubtotalTemplate='manager/subtotal_order.tpl';

			Base::$sText.=$oTable->getTable();
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseOrder(&$aItem)
	{
		require_once(SERVER_PATH.'/class/core/String.php');
		require_once(SERVER_PATH.'/class/system/Currency.php');

		if ($aItem && Base::$aRequest['search']['price'] || Base::$aRequest['search']['price2']) {
			if (Base::$aRequest['search']['price']) 
				$dBoardPrice1 = doubleval(Base::$aRequest['search']['price']);
			if (Base::$aRequest['search']['price2'])
				$dBoardPrice2 = doubleval(Base::$aRequest['search']['price2']);
			$dOrderSubtotal = 0;
			foreach($aItem as $key => $value) {
				$dPrice = doubleval(Currency::PrintPrice($value['price'],0,2,'<none>')); 
				if (Base::$aRequest['search']['price'] && Base::$aRequest['search']['price2']) {
					if ($dPrice < $dBoardPrice1 || $dPrice > $dBoardPrice2) {
						unset($aItem[$key]);
						continue;
					}
				}
				elseif (Base::$aRequest['search']['price']) {
					if ($dPrice < $dBoardPrice1) {
						unset($aItem[$key]);
						continue;
					}
				}
				elseif (Base::$aRequest['search']['price2']) {
					if ($dPrice > $dBoardPrice2) {
						unset($aItem[$key]);
						continue;
					}
				}
				$dOrderSubtotal += $value['number'] * $dPrice;				
			}
			$aItem = array_values($aItem);
			Base::$tpl->assign('dOrderSubtotal',$dOrderSubtotal);
		}
		
		if ($aItem) {
			// get info by garage
			foreach($aItem as $key => $value) {
				$aMass[$value[id]] = $value[id];
			}
			$aInfo = Db::GetAssoc("SELECT id_user, count(*) FROM user_auto WHERE id_user
				IN (".implode(",",$aMass).") GROUP BY id_user");

			// get info group
			$aInfoGroup = Db::GetAssoc("SELECT id_group, count(*) FROM user_provider_group GROUP BY id_group");
			
			foreach($aItem as $key => $value) {
				if ($aInfo[$value['id']])
					$aItem[$key]['cnt_garage'] = $aInfo[$value['id']];

				if ($aInfoGroup[$value['id_group']])
					$aItem[$key]['cnt_group'] = $aInfoGroup[$value['id_group']];
				
				$aOrderId[]=$value['id'];

				$aItem[$key]['name']="<b>".$value['name'].
				"</b><br>".String::FirstNwords($value['customer_comment'],5);
				$aItem[$key]['total']=$value['number']*Currency::PrintPrice($value['price']);
				
				$iPriceOriginal = $value['price_original'];
				if ($value['price_original_one_currency']!=0 &&
				$value['price_original_one_currency']!=$value['price_original'])
					$iPriceOriginal = $value['price_original_one_currency'];
				
				$aItem[$key]['total_real']=$value['number']*Currency::PrintPrice($iPriceOriginal);
				$aItem[$key]['discount']=max(array($value['discount_static']
				, $value['discount_dynamic'], $value['group_discount']));
				$aItem[$key]['debt']=Currency::PrintPrice(
				max(array($value['user_debt'], $value['group_debt'])),$value['code_currency']);
			}

			$aHistory=Base::$db->getAll("select * from cart_log
				where id_cart in (".implode(',',$aOrderId).")");
			if ($aHistory) foreach($aHistory as $key => $value) {
				//$value['post']=DateFormat::getDateTime($value['post']);
				if ($value['is_customer_visible']==0 && !Auth::$aUser['is_super_manager'])
					continue;
				$aHistoryHash[$value['id_cart']][]=$value;
			}

			foreach($aItem as $key => $value) {
				if ($aHistoryHash && in_array($value['id'],array_keys($aHistoryHash)) ) {
					$aItem[$key]['history']=$aHistoryHash[$value['id']];
					$value['history'] = $aHistoryHash[$value['id']];
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeStatus()
	{
		if (Base::$aRequest['row_check']) {
			foreach (Base::$aRequest['row_check'] as $sKey => $sValue) {
				$sMessage.=$sValue.":".$this->ProcessOrderStatus($sValue,Base::$aRequest['order_status'])." ";
			}
			Form::RedirectAuto("&aMessage[MT_NOTICE_NT]=".$sMessage);
		} elseif (Base::$aRequest['id'] && Base::$aRequest['order_status']) {
			//Base::$oResponse->addAlert('1');
			$sMessage=$this->ProcessOrderStatus(Base::$aRequest['id'],Base::$aRequest['order_status']);
			Base::$oResponse->AddAlert($sMessage);
		} else {
			Form::RedirectAuto("&aMessage[MT_ERROR]=Need to check item");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AgreeGrowth()
	{
		if (Base::$aRequest['id']) {
			$aCart['is_agree_growth']=(Base::$aRequest['checked'] ? 1:0);
			Base::$db->AutoExecute('cart',$aCart,'UPDATE',"id='".Base::$aRequest['id']."'");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Reorder()
	{
		$aCart=Base::$db->getRow("
				select cg.*,u.*,uc.*,c.*,u.login, uc.name as customer_name
				from cart c
				inner join user u on c.id_user=u.id
				inner join user_customer uc on uc.id_user=u.id
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user_account ua on ua.id_user=u.id

				where 1=1 and c.type_='order'
				and c.id='".Base::$aRequest['id']."'
				and c.id_user in (".$this->sCustomerSql.")");

		if (Base::$aRequest['is_post']) {

			if (!Base::$aRequest['code'] || !Base::$aRequest['price'] || !Base::$aRequest['number']) {
				$sError="Please, fill the required fields";
			}
			if (Base::$aRequest['price'] < $aCart['price_original']) {
				$sError="Reorders price is less than price original";
			}

			if ($sError) Base::$tpl->assign('aData',Base::$aRequest);
			else {
				//[----- UPDATE -----------------------------------------------------]
				Debt::CheckPayDebt(Base::$aRequest['id']);

				if ($aCart['order_status']!='refused') {
					Finance::Deposit($aCart['id_user'],Currency::PrintPrice($aCart['price'],null,0,'<none>')*$aCart['number'],Language::getMessage('Reordered Detal'),
					$aCart['id_cart_package'],'internal','cart','',5);

					//InvoiceAccountLog::AddItem($aCart['id'],-$aCart['price']*$aCart['number'],Language::GetMessage('ii_reorder'));
				}


				Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment)
				values ('".Base::$aRequest['id']."',UNIX_TIMESTAMP(),'reorder'
				,'".Language::getMessage('New status').": ".Language::getMessage(Base::$aRequest['order_status'])."')");

				Base::$db->Execute("update cart set
				code='".Base::$aRequest['code']."',
				price='".Base::$aRequest['price']."',
				number='".Base::$aRequest['number']."',
				order_status='".Base::$aRequest['order_status']."',
				manager_comment= '".Base::$aRequest['manager_comment']."',
				name_translate='".Base::$aRequest['name_translate']."',
				weight='".Base::$aRequest['weight']."',
				sign='".Base::$aRequest['sign']."'
				where id='".Base::$aRequest['id']."'
				and id_user in (".$this->sCustomerSql.") ");

				if (Base::$aRequest['order_status']!='refused') {
					Finance::Deposit($aCart['id_user'],-Base::$aRequest['price']*Base::$aRequest['number']
					,Language::getMessage('Detal #').Base::$aRequest['id'],$aCart['id_cart_package'],'internal','cart','',5);

					//InvoiceAccountLog::AddItem(Base::$aRequest['id'],Base::$aRequest['price']*Base::$aRequest['number']
					//,Language::GetMessage('ii_reorder'));
				}
				//[----- END UPDATE -------------------------------------------------]
				Form::AfterReturn('manager_order');
			}
		}

		if (!Base::$aRequest['is_post']) {

			if (!$aCart) Base::Redirect('/?action=manager_order');

			Base::$tpl->assign('aData',$aCart);
		}

		Form::BeforeReturn('manager_order','manager_reorder');

		include(SERVER_PATH.'/include/order_status_config.php');
		Base::$tpl->assign('aOrderStatusConfig',$aOrderStatusConfig['work']);

		foreach ($aOrderStatusConfig['work'] as $item){
		    $aOrderStatus[$item]=Language::GetMessage($item);
		}
		
		$aField['code']=array('title'=>'Code','type'=>'input','value'=>$aCart['code'],'name'=>'code');
		$aField['price']=array('title'=>'Price','type'=>'input','value'=>$aCart['price'],'name'=>'price');
		$aField['number']=array('title'=>'Number','type'=>'input','value'=>$aCart['number'],'name'=>'number');
		$aField['order_status']=array('title'=>'New Order Status','type'=>'select','options'=>$aOrderStatus,'name'=>'order_status');
		$aField['manager_comment']=array('title'=>'Comment','type'=>'textarea','name'=>'manager_comment','value'=>$aCart['manager_comment']);
		$aField['name_translate']=array('title'=>'Name Translate','type'=>'textarea','name'=>'name_translate','value'=>$aCart['name_translate']);
		$aField['weight']=array('title'=>'Weight','type'=>'input','value'=>$aCart['weight'],'name'=>'weight');
		$aField['sign']=array('title'=>'Sign','type'=>'input','value'=>$aCart['sign'],'name'=>'sign');
		
		$aData=array(
		'sHeader'=>"method=post",
		'sTitle'=>"Reorder Cart item",
		//'sContent'=>Base::$tpl->fetch('manager/form_reorder.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Apply',
		'sSubmitAction'=>'manager_reorder',
		'sReturnAction'=>'manager_order',
		'sReturnButton'=>'<< Return',
		'bConfirmSubmit'=>true,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
		return;
	}
	//-----------------------------------------------------------------------------------------------
	//	public function Archive() {
	//		if (Base::$aRequest['is_archive']) $iIsArchive=1;
	//		$sQuery="update cart set
	//				is_archive='".$iIsArchive."'
	//			where id='".Base::$aRequest['id']."'
	//				and type_='order'
	//				and id_user in (".$this->sCustomerSql.")
	//				";
	//		Base::$db->Execute($sQuery);
	//		$this->OrderList();
	//	}
	//-----------------------------------------------------------------------------------------------
	//	public function XajaxRequest($oReponse) {
	//		Base::$db->Execute("select * from user");
	//		$oReponse->addAlert('Ok');
	//	}

	//-----------------------------------------------------------------------------------------------
	public function Bill()
	{
		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['amount']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_bill_add';
				Base::$tpl->assign('aData',Base::$aRequest);
			}
			else {
				if (!Base::$aRequest['id']) {
					//[----- INSERT -----------------------------------------------------]
					$sQuery="insert into bill(id_user,amount,code_template,post)
        			        values('".Base::$aRequest['id_user']."','".Base::$aRequest['amount']."'
        			        	,'".Base::$aRequest['code_template']."',UNIX_TIMESTAMP())";
					//[----- END INSERT -------------------------------------------------]
				}
				else {
					//[----- UPDATE -----------------------------------------------------]
					$sQuery="update bill set
							id_user='".Base::$aRequest['id_user']."',
							code_template='".Base::$aRequest['code_template']."',
							amount='".Base::$aRequest['amount']."'
				               where id='".Base::$aRequest['id']."'
				               	and id_user in (".$this->sCustomerSql.")
				               ";
					//[----- END UPDATE -------------------------------------------------]
				}
				Base::$db->Execute($sQuery);
				Base::Redirect("/?action=manager_bill");
			}
		}

		if (Base::$aRequest['action']=='manager_bill_add' || Base::$aRequest['action']=='manager_bill_edit') {
			if (Base::$aRequest['action']=='manager_bill_edit') {
				$aBill=Base::$db->getRow("select * from bill where id='".Base::$aRequest['id']."'
						and id_user in (".$this->sCustomerSql.")
					");
				Base::$tpl->assign('aData',$aBill);
			}

			Base::$tpl->assign('aUser',$aUser=$this->getCustomerList());
			Base::$tpl->assign('aBillTemplate',$aBillTemplate=Base::$db->getAll("select * from template where type_='bill'"));

			foreach ($aUser as $item){
			    $aUserOptions[$item['id']]=$item['login'].' - '.$item['name'].' (customer_group_name)';
			}
			foreach ($aBillTemplate as $item){
			    $aBillTemplateOptions[$item['code']]=$item['name'];
			}
			$aField['id_user']=array('title'=>'Customer','type'=>'select','options'=>$aUserOptions,'selected'=>Base::$aRequest['id_user']=$aUser['id'],'name'=>'id_user');
			$aField['code_template']=array('title'=>'Bill Template','type'=>'select','options'=>$aBillTemplateOptions,'selected'=>Base::$aRequest['code_template']=$aBillTemplate['code'],'name'=>'code_template');
			$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aBill['amount'],'name'=>'amount');
			$aField['code_template']=array('type'=>'hidden','name'=>'code_template','value'=>'simple_bill');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"User Bill",
			//'sContent'=>Base::$tpl->fetch('manager/form_bill.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_bill',
			'sReturnButton'=>'<< Return',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();

			return;
		}

		if (Base::$aRequest['action']=='manager_bill_delete') {
			Base::$db->Execute("delete from bill where id='".Base::$aRequest['id']."'
					and id_user in (".$this->sCustomerSql.")");
		}

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->sSql="select cg.*,uc.*, ua.* ,u.*, cg.name as group_name
				, b.*, t.name as template_name
				, m.login as manager_login
			from bill b, template t, user u
				inner join user_customer uc on u.id=uc.id_user
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user_account ua on ua.id_user=u.id
				inner join user m on uc.id_manager=m.id

			where 1=1 and b.code_template=t.code
				and b.id_user=u.id
				and b.id_user in (".$this->sCustomerSql.")
				group by b.id
			";
		$oTable->aOrdered=" order by b.post desc";
		$oTable->aColumn=array(
		'login'=>array('sTitle'=>'Customer','sWidth'=>'40%'),
		'amount'=>array('sTitle'=>'Amount','sWidth'=>'150px'),
		'template'=>array('sTitle'=>'Template','sWidth'=>'30%'),
		'post'=>array('sTitle'=>'Date','sWidth'=>'40%'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='manager/row_bill.tpl';
		$oTable->sAddButton="Add";
		$oTable->sAddAction="manager_bill_add";
		//$oTable->aCallback=array($this,'CallParseBill');

		Base::$sText.=$oTable->getTable("Customer Bills");
	}
	//-----------------------------------------------------------------------------------------------
	//	public function CallParseBill(&$aItem) {
	//		require_once(SERVER_PATH.'/class/core/DateFormat.php');
	//		if ($aItem) foreach($aItem as $key => $value) {
	//			$aItem[$key]['post_date']=DateFormat::getDateTime($value['post']);
	//		}
	//	}
	//-----------------------------------------------------------------------------------------------
	public function ProcessOrderStatus($iId='',$sOrderStatus='',$sComment='',$sIdProviderOrder='',$dProviderPrice=''
	,$sIdProviderInvoice='',$sCustomValue='')
	{
		$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
		
		if (!$iId) $iId=Base::$aRequest['id'];
		if (!$sOrderStatus) $sOrderStatus=Base::$aRequest['order_status'];
		if (!$sComment) $sComment=Base::$aRequest['comment'];
		if (!$sIdProviderOrder) $sIdProviderOrder=Base::$aRequest['id_provider_order'];
		if (!$sIdProviderInvoice) $sIdProviderInvoice=Base::$aRequest['id_provider_invoice'];
		if (!$dProviderPrice) $dProviderPrice=Base::$aRequest['provider_price'];
		if (!$sCustomValue) $sCustomValue=Base::$aRequest['custom_value'];
		$iId=trim($iId);
		$sOrderStatus=trim($sOrderStatus);
		$sComment=trim($sComment);
		$sIdProviderOrder=trim($sIdProviderOrder);
		$sIdProviderInvoice=trim($sIdProviderInvoice);
		$sCustomValue=trim($sCustomValue);

		// check denied change status
		$sDeniedMessage = Manager::CheckDeniedChangeStatus($iId,$sOrderStatus);
		if ($sDeniedMessage)
			return $sDeniedMessage;
			
		$aCart=Db::GetRow(Base::GetSql('Part/Search',array(
		'id_cart'=> $iId,
		'where'=>" and c.type_='order' ",
		)));
		//and prg.id in (".implode(',',$this->aUserManagerRegionId).")

		if (!$aCart) return Language::getMessage('No such order or access denied by region and other permissions');
		$this->sCurrentOrderStatus=$aCart['order_status'];

		require(SERVER_PATH.'/include/order_status_config.php');

		//		if (!$sOrderStatus || !(in_array($sOrderStatus,array_keys($aOrderStatusConfig))
		//		|| in_array($sOrderStatus,$aUnstateOrderStatus)) )
		//		return Language::getMessage('Error: Not valid next status');

		if (!in_array($sOrderStatus,$aUnstateOrderStatus) )	{
			if (!$aOrderStatusConfig[$aCart['order_status']]
			|| !in_array($sOrderStatus,$aOrderStatusConfig[$aCart['order_status']])
			|| $sOrderStatus==$aCart['order_status']
			)
			return Language::getMessage('Error: Not valid next status').' ['.Language::getMessage($aCart['order_status']).' => '.Language::getMessage($sOrderStatus).']';
		}

		//	if ($sOrderStatus==$aCart['order_status']) return Language::getMessage('Error: The same next status: not changed.');

		switch ($sOrderStatus) {
			case 'change_price':
				$aChangeResult=$this->ChangeCart($aCart,$sOrderStatus,$sCustomValue);
				//Price::AddItem($aCart,$dProviderPrice);
				$this->SetPriceTotalCartPackage($aCart);
				if (!$aChangeResult['bResult']) return Language::getMessage($aChangeResult['sMessage']);
				break;
			case 'change_quantity':
				$aChangeResult=$this->ChangeCart($aCart,$sOrderStatus,$sCustomValue);
				$this->SetPriceTotalCartPackage($aCart);
				if (!$aChangeResult['bResult']) return Language::getMessage($aChangeResult['sMessage']);
				break;
			case 'change_code':
				$aChangeResult=$this->ChangeCart($aCart,$sOrderStatus,$sCustomValue);
				if (!$aChangeResult['bResult']) return Language::getMessage($aChangeResult['sMessage']);
				break;
		}

		if ($sOrderStatus=='return_customer')  {
			// check split item
			if ($sCustomValue != 0 && $aCart['number'] > $sCustomValue) {
				$iLeftNumber = $aCart['number']-$sCustomValue;
				$sComment = Language::getMessage('split_cart_item_return_customer').' '.$aCart['number'].' => '.$iLeftNumber;
				$sSql="update cart set number='$iLeftNumber' where id='{$aCart['id']}'";
				DB::Execute("update cart set number_before_change=".$aCart['number']." where number_before_change=0 and id='".$aCart['id']."'");
				Db::Execute($sSql);
				Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager)
				values ('$iId',UNIX_TIMESTAMP(),'change_quantity'
				,'$sComment',".Auth::$aUser["id"].")");
		
				$aCart['number']=$sCustomValue;
				unset($aCart['id']);
				Db::AutoExecute("cart", $aCart);
				$iId = Db::InsertId();
				$aCart['id'] = $iId;
			}

			Message::CreateDelayedNotification($aCart['id_user'],'order_is_return_customer'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
		}

		if (!in_array($sOrderStatus,$aUnstateOrderStatus) )
		{
			if ($sOrderStatus=='work' || $sOrderStatus=='return_store' || $sOrderStatus=='return_provider') 
					$sDateUpdate=" ,post=UNIX_TIMESTAMP(),post_date=now()";
			Base::$db->Execute("update cart set
					order_status='$sOrderStatus'
					$sDateUpdate
					where id='$iId' ");
		}
		else {
			if ($sOrderStatus=='change_price') $sCustomValue=$aChangeResult['sPreviousNext'];
			if ($sOrderStatus=='change_quantity') $sCustomValue=$aChangeResult['sPreviousNext'];
			if ($sOrderStatus=='change_code') $sCustomValue=$aChangeResult['sPreviousNext'];
			$sComment=mysql_escape_string(Language::getMessage($sOrderStatus).': '.$sCustomValue.' '.$sComment);
		}

		if ($sIdProviderOrder)  $sIdProviderOrderSqlUpdate=" , id_provider_order='$sIdProviderOrder'";
		if ($sIdProviderInvoice)  $sIdProviderInvoiceSqlUpdate=" , id_provider_invoice='$sIdProviderInvoice'";
		if ($dProviderPrice)  $sProviderPriceSqlUpdate=" , provider_price='$dProviderPrice'";
		if ($sComment)  $sCommentSqlUpdate=" , manager_comment= concat(manager_comment,'".$sComment."',' ; ') ";

		if ($sComment || $sIdProviderOrder || $sIdProviderInvoice || $dProviderPrice) {
			Base::$db->Execute("update cart set id_user=id_user
				$sIdProviderOrderSqlUpdate
				$sProviderPriceSqlUpdate
				$sIdProviderInvoiceSqlUpdate
				$sCommentSqlUpdate
						where id='$iId'");
		}
		// for buh amount module
		//$dTotal=Currency::PrintPrice($aCart['price'],$iId_GeneralCurrencyCode,2,"<none>")*$aCart['number'];
		if ($sOrderStatus=='refused') $this->SetPriceTotalCartPackage($aCart);

		if ($sOrderStatus=='return_store' || $sOrderStatus=='return_provider')
			$sComment = Language::getMessage($sOrderStatus).' '.$aCart['id'].' '.$sComment;	
		
		if(!$aChangeResult['bIdenticalValues'] /*&& $sOrderStatus!='return_customer'*/)
			Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager)
				values ('$iId',UNIX_TIMESTAMP(),'$sOrderStatus'
					,'$sComment',".Auth::$aUser["id"].")");

		$aCart['comment']=$sComment;
		//require_once(SERVER_PATH.'/class/core/DateFormat.php');
		$aCart['date']=DateFormat::getDateTime(time());

		$aCartManager=Db::GetRow(Base::GetSql('Manager',array(
		'id'=>$aCart['id_manager'],
		)));
		$aCartCustomer=Db::GetRow(Base::GetSql('Customer',array(
		'id'=>$aCart['id_user'],
		)));

		switch ($sOrderStatus) {
			case 'work':
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_work'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
				break;
			case 'confirmed':
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_confirmed'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
				break;
			case 'road':
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_road'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
				break;
			case 'store':
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_store'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
				$aCart['order_status']=$sOrderStatus;
				
				// Поступление детали на склад - поставщик
				$aOperation = Db::GetRow("Select * from user_account_type_operation where code='pay_provider'");
// 				$iIdProviderCurrency=Db::GetOne("select id_currency from user_provider where id_user='".$aCart['id_provider']."' ");
// 				$dCurrencyRate=Db::GetOne("select value from currency where id='".$iIdProviderCurrency."' ");
//				$dCurrencyRate=1;
				
				$iPriceOriginal = $aCart['price_original'];
				if ($aCart['price_original_one_currency']!=0 &&
					$aCart['price_original_one_currency']!=$aCart['price_original'])
					$iPriceOriginal = $aCart['price_original_one_currency'];
				
//				$dPriceInput=$iPriceOriginal/$dCurrencyRate;
				Finance::Deposit($aCart['id_provider'],($iPriceOriginal*$aCart['number']),$aOperation['name'],
					$aCart['id_cart_package'],'interval','',0,0,0,$aOperation['code'],0,0,false,0,'','',0,$aCart['id']);
				
				break;
    		case 'sent':
    		    Message::CreateDelayedNotification($aCart['id_user'],'order_is_sent'
    		    ,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
    		    $aCart['order_status']=$sOrderStatus;
    		    break;
			case 'end':
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_end'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
				break;
			case 'refused':
			    
			    $aCartPackage=Db::GetRow("select * from cart_package where id='".$aCart['id_cart_package']."' ");
			    if($aCartPackage['order_status']=='pending') {
			        // с клиента еще не было списания - ничего не возвращаем
			    } else {
			        // заказ брали в работу или сразу удаление детали?
			        $iExistWorkPackage = Db::getOne("Select id from user_account_log where custom_id=".$aCart['id_cart_package']." and operation='pending_work'");
			        if ($iExistWorkPackage) {
			        // с клиента было списание - возвращаем
			        $aOperation = Db::GetRow("Select * from user_account_type_operation where code='refused'");
			        Finance::Deposit($aCart['id_user'],(Currency::PrintPrice($aCart['price'],null,0,'<none>')*$aCart['number']),$aOperation['name'],
			        	$aCart['id_cart_package'],'interval','',0,0,0,$aOperation['code'],0,0,true,0,'','',0,$aCart['id']);
			        }
			        // с поставщика было списание - возвращаем
    			    /*if($aCart['order_status']=='store') {
    			        $aOperation = Db::GetRow("Select * from user_account_type_operation where code='refused_provider'");
    			        $dCurrencyRate=1;
    			        $dPriceInput=($aCart['price_real'])/$dCurrencyRate;
    			        Finance::Deposit($aCart['id_provider'],($dPriceInput*$aCart['number']),$aOperation['name'],$aCart['id_cart_package'],'interval','',
    			        	0,0,0,$aOperation['code'],0,0,false,0,'','',0,$aCart['id']);
    			    }*/
			    }

				Message::CreateDelayedNotification($aCart['id_user'],'order_is_refused'
				,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);

				$aCart['order_status']=$sOrderStatus;
				break;
				//			case 'reclamation':
				//				Message::CreateDelayedNotification($aCart['id_user'],'order_is_reclamation'
				//				,array('aCart'=>$aCart),true,$aCart['id']);
				//
				//				Db::Execute("update cart_package set order_status='work' where id=".$aCart['id_cart_package']);
				//				break;
			case 'reclamation':
				$dAmount=Currency::PrintPrice($aCart['price'],$iId_GeneralCurrencyCode,0,"<none>")*$aCart['number'];
				/*if(!Base::$aRequest['commission'])Base::$aRequest['commission']=0;
				if(Base::$aRequest['commission_type']=='percent'){
					$dAmount=$dAmount*(1-Base::$aRequest['commission']/100);
				}elseif(Base::$aRequest['commission_type']=='absolute'){
					if($dAmount>=Base::$aRequest['commission'])
						$dAmount=$dAmount-Base::$aRequest['commission'];
					else
						$dAmount=0;
				}*/
				$sName = Language::getMessage('return for').' '.$aCart['code'].' '.$aCart['cat_title'].' ['.$aCart['number'].' '.Language::getMessage('col.').']';
				Finance::Deposit($aCart['id_user'],$dAmount
				,Language::getMessage("Returned order #")." ".$aCart['id'].Finance::GetDescriptionDebt($dAmount,$sName)
				,$aCart['id_cart_package'],'cart','',3335,361);
				
				Message::CreateDelayedNotification($aCart['id_user'],'order_is_returned'
						,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
			
				/*if($aCartCustomer['id_parent'])
					Finance::Deposit($aCartCustomer['id_parent'],(-1)*($aCart['price_parent_margin']*$aCart['number'])
							,Language::getMessage("returned Vip order #")." ".$aCart['id'].Finance::GetDescriptionDebt($aCart['price_parent_margin']*$aCart['number'])
							,$aCart['id'],'cart','',3341,361);*/
			
				$aCart['order_status']=$sOrderStatus;
				break;
				case 'return_store':
					if($aCart['order_status']=='return_customer') {
						$sDecription = Language::getMessage($sOrderStatus).' '.$aCart['id'];
						Finance::Deposit($aCart['id_user'],(Currency::PrintPrice($aCart['price'],null,0,'<none>')*$aCart['number']),'',$aCart['id_cart_package'],'interval','return_store');
						
						Message::CreateDelayedNotification($aCart['id_user'],'order_is_return_store_provider'
								,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
					
						$aCart['order_status']=$sOrderStatus;
					}
				break;
				case 'return_provider':
					if($aCart['order_status']=='return_customer') {
						// возврат заказчику
						$sDecription = Language::getMessage($sOrderStatus).' '.$aCart['id'];
						Finance::Deposit($aCart['id_user'],(Currency::PrintPrice($aCart['price'],null,0,'<none>')*$aCart['number']),$sDecription,$aCart['id_cart_package'],'interval','return_store');
				
						Message::CreateDelayedNotification($aCart['id_user'],'order_is_return_store_provider'
								,array('aCart'=>$aCart,'aManager'=>$aCartManager,'aCustomer'=>$aCartCustomer),true,$aCart['id']);
						
						// возврат поставщику - накладная возврата
						$iPriceOriginal = $aCart['price_original'];
						if ($aCart['price_original_one_currency']!=0 &&
							$aCart['price_original_one_currency']!=$aCart['price_original'])
							$iPriceOriginal = $aCart['price_original_one_currency'];
						
						Finance::Deposit($aCart['id_provider'],($iPriceOriginal*$aCart['number']),$sDecription,$aCart['id_cart_package'],'interval'
							,'return_provider',0,0,0,'',0,0,false,0,'','',0,$aCart['id']);
						
						$aCart['order_status']=$sOrderStatus;
					}
				break;
				case 'accrued':
					$aCart['order_status']=$sOrderStatus;
					Cron::AssumedCartPackage($aCart['id_cart_package'],$sOrderStatus);
					// todo set flag call
					Db::Execute("Update user_manager set is_warning=1");
					Auth::$aUser['is_warning'] = 1;
				break;
				case 'removed':
					$aCart['order_status']=$sOrderStatus;
					Cron::AssumedCartPackage($aCart['id_cart_package'],$sOrderStatus);
					// todo set flag call all managers
					Db::Execute("Update user_manager set is_warning=1");
					Auth::$aUser['is_warning'] = 1;
				break;
		}

		if (!in_array($sOrderStatus,$aUnstateOrderStatus) )
		{
			Cron::CloseCartPackage($aCart['id_cart_package'],$sOrderStatus);
		}
		return Language::getMessage('Changed ok.');
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeCart($aCart,$sOrderStatus,$sCustomValue)
	{
		$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
		
		$aCartManager=Db::GetRow(Base::GetSql('Manager',array(
			'id'=>$aCart['id_manager'],
		)));
		$aCartCustomer=Db::GetRow(Base::GetSql('Customer',array(
			'id'=>$aCart['id_user'],
		)));
		$aCart['price'] = Currency::PrintPrice($aCart['price'],$iId_GeneralCurrencyCode,0,"<none>");
		
		switch ($sOrderStatus) {
			case 'change_price':
				if ( stripos($aCart['sign'],'AGRE')===false && $sCustomValue>$aCart['price_original']
				&& !Base::$aRequest['ignore_confirm_growth']){
					return array('bResult'=>false,'sMessage'=>'Not valid cart sign');
				}
				//if (!Base::$aConstant['price_growth']['value']) $dPriceGrowth=10;
				$dPriceGrowth=Base::GetConstant('price_growth',10);

				if (!$aCart['price_original'])
				return array('bResult'=>false,'sMessage'=>'empty original price');
				if (!is_numeric($sCustomValue) || !$sCustomValue || $sCustomValue<=0)
				return array('bResult'=>false,'sMessage'=>'new price is not valid');

				if ( (($sCustomValue-$aCart['price']) / $aCart['price']) <= ($dPriceGrowth/100)
				|| $sCustomValue<$aCart['price'] || Base::$aRequest['ignore_confirm_growth'])
				{
					$sPreviousValue=$aCart['price'];
					$sNextValue=$sCustomValue;

					$dAmount=$aCart['number']*($aCart['price']-$sCustomValue);
					$sSql="update cart set price='".$sCustomValue."' where id='{$aCart['id']}'";
					DB::Execute("update cart set price_before_change='".$sPreviousValue."' where price_before_change=0 and id='".$aCart['id']."'");

					$aCart['order_status']=$sOrderStatus;
					$aCart['comment']=Language::getMessage('change_price_comment price_difference').':'
					.($sCustomValue-$aCart['price']);

				}
				else return array('bResult'=>false,'sMessage'=>'missed new percentage '.$dPriceGrowth.'%');
				break;

			case 'change_quantity':
				if ( stripos($aCart['sign'],'QUAN')!==false)
				return array('bResult'=>false,'sMessage'=>'Not valid cart sign');
				//if ( $sCustomValue>=$aCart['number'] || !$sCustomValue)
				if (!$sCustomValue)
				return array('bResult'=>false,'sMessage'=>'Not valid number');

				$sPreviousValue=$aCart['number'];
				$sNextValue=$sCustomValue;

				$dAmount=$aCart['price']*($aCart['number'] - $sCustomValue);
				$sSql="update cart set number='$sCustomValue' where id='{$aCart['id']}'";
				DB::Execute("update cart set number_before_change=".$sPreviousValue." where number_before_change=0 and id='".$aCart['id']."'");
				break;

			case 'change_code':
				if (!$sCustomValue)	return array('bResult'=>false,'sMessage'=>'Not valid code');

				$sPreviousValue=($aCart['code_changed'] ? $aCart['code_changed'] : $aCart['code']);
				$sNextValue=$sCustomValue;
				
				$sBrand=DB::GetOne("select c.title from cat c where c.pref='".$aCart['pref']."'");
				if($aCart['pref_changed'])
					$sChBrand=DB::GetOne("select c.title from cat c where c.pref='".$aCart['pref_changed']."'");
				$sPreviousBrand=($aCart['pref_changed'] ? $sChBrand : $sBrand);
				$sNextBrand=DB::GetOne("select c.title from cat c where c.pref='".Base::$aRequest['pref_changed']."'");

				$aCart['order_status']=$sOrderStatus;
				$aCart['comment']=Language::getMessage('change_code_comment new-code').':'
				.Base::$aRequest['pref_changed']."_".$sCustomValue;

				if ( stripos($aCart['sign'],'ONLY')!==false) return array('bResult'=>false,'sMessage'=>'Not valid cart sign');

				$sSql="update cart set code_changed='".Catalog::StripCode($sCustomValue)."', pref_changed='"
				.Base::$aRequest['pref_changed']."' where id='{$aCart['id']}'";
				
				if($sNextBrand!=$sPreviousBrand) {
					DB::Execute("insert into cart_log (id_cart, post, order_status, comment, id_user_manager)
					values (".$aCart['id'].", UNIX_TIMESTAMP(), 'change_brand', '".$sPreviousBrand." => ".$sNextBrand."', ".Auth::$aUser['id'].")");  
            }

				break;
		}
		if ($dAmount) {
			// check exist work status order
			$iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aCart['id_cart_package']." and operation='pending_work'");
			if ($iWorkPayAlready)
				Finance::Deposit($aCart['id_user'],$dAmount,Language::getMessage($sOrderStatus).' '.$aCart['id']
				." : $sPreviousValue => $sNextValue",$aCart['id_cart_package']
				,'internal','cart',0,6,0,'',0,0,true,0,'','',0,$aCart['id']);
			//			InvoiceAccountLog::AddItem($aCart['id'],-$dAmount
			//			,Language::GetMessage('ii_change_price_quantity')." $sPreviousValue => $sNextValue");
		}
		if ($sSql) Base::$db->Execute($sSql);
		$aCart['change_date'] = date('Y-m-d H:i:s');
		switch($sOrderStatus)
		{
			case 'change_price':
				$sSubject = 'Price of part in order is changed';
				$aCart['price'] = $sCustomValue;
				$aCart['price_before_change'] = ($aCart['price_before_change']>0 ? $aCart['price_before_change'] : $sPreviousValue);
				//$aData=array('cart_data'=>$aCart);
				$sCode='change_price';
				break;
			case 'change_quantity':
				$sSubject = 'Quantity of parts in order is changed';
				$aCart['number'] = $sCustomValue;
				$aCart['number_before_change'] = ($aCart['number_before_change'] ? $aCart['number_before_change'] : $sPreviousValue);
				//$aData=array('cart_data'=>$aCart);
				$sCode='change_quantity';
				break;
			case 'change_code':
				$sSubject = 'Code of part in order is changed';
				$aCart['code_changed'] = $sCustomValue;
				$aCart['pref_changed'] = Base::$aRequest['pref_changed'];
				$aCart['cat_name_changed'] = $sNextBrand;
				$aCart['new_brand'] = ($aCart['pref_changed']!=$aCart['pref'] ? $sNextBrand : '');
				//$aData=array('cart_data'=>$aCart);
				$sCode='change_code';
				break;
		};
		$aCart['date']=DateFormat::getDateTime(time());
		Message::CreateDelayedNotification($aCart['id_user'], $sCode
		,array('aCart'=>$aCart, 'info' => $aCartCustomer, 'aManager' => $aCartManager),true,$aCart['id']);
		$aChangeResult=array(
		'bResult'=>true,
		'sMessage'=>Language::getMessage('Changed ok. But notification not created'),
		'sPreviousNext'=>" $sPreviousValue => $sNextValue",
		'bIdenticalValues'=>($sPreviousValue==$sNextValue ? true : false)
		);
		return $aChangeResult;
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequest()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'vin_request');

		// ######### Edit #########
		if ( Base::$aRequest['action']=='manager_vin_request_edit') {

			Form::BeforeReturn('manager_vin_request');

			$aVinRequest=Base::$db->getRow(Base::GetSql('VinRequest',array(
			'id'=>Base::$aRequest['id'],
			//'id_in'=>$this->GetVinIdList(),
			)));

			//			if (Auth::$aUser['id_vin_request_fixed']!=Base::$aRequest['id']
			//			&& Auth::$aUser['id_vin_request_fixed']
			//			&& !Auth::$aUser['is_sub_manager']
			//			&& !Auth::$aUser['is_super_manager']
			//			&& Auth::$aUser['id'] != $aVinRequest['id_manager_fixed']
			//			) Base::Redirect('/?action=manager_vin_request');


			if (!$aVinRequest) Base::Redirect('/?action=manager_vin_request');
			if ($aVinRequest['order_status']=='new') {
				//				if (!$aVinRequest['id_manager_fixed']) {
				//					$sSet.=",id_manager_fixed='".Auth::$aUser['id']."'";
				//					Base::$db->Execute("update user_manager set id_vin_request_fixed='".Base::$aRequest['id']."'
				//						where id_user='".Auth::$aUser['id']."'");
				//					Auth::$aUser['id_vin_request_fixed']=Base::$aRequest['id'];
				//					Base::$tpl->assign('aAuthUser',Auth::$aUser);
				//					Base::$db->Execute("update user_customer set id_manager='".Auth::$aUser['id']."'
				//						where id_user='".$aVinRequest['id_user']."'");
				//				}
				Base::$db->Execute("update vin_request set order_status='work' $sSet
					where id='".Base::$aRequest['id']."'");
			}

			require_once(SERVER_PATH.'/class/system/Currency.php');
			$aVinRequest['discount']=max(array($aVinRequest['discount_static']
			, $aVinRequest['discount_dynamic'], $aVinRequest['group_discount']));
			$aVinRequest['debt']=Currency::PrintPrice(
			max(array($aVinRequest['user_debt'], $aVinRequest['group_debt'])),$aVinRequest['code_currency']);

			Base::$tpl->assign('aData',$aVinRequest);

			if($aVinRequest['mobile']) $aField['mobile']=array('title'=>'Mobile','type'=>'text','value'=>$aVinRequest['mobile']);
			$aField['marka']=array('title'=>'Marka','type'=>'text','value'=>$aVinRequest['marka']);
			$aField['vin']=array('title'=>'VIN','type'=>'text','value'=>$aVinRequest['vin']);
			$aField['model']=array('title'=>'Model','type'=>'text','value'=>$aVinRequest['model']);
			$aField['engine']=array('title'=>'Engine','type'=>'text','value'=>$aVinRequest['engine']);
			$aField['country_producer']=array('title'=>'Country producer','type'=>'text','value'=>$aVinRequest['country_producer']);
			$aField['month_year']=array('title'=>'Month/Year','type'=>'text','value'=>$aVinRequest['month'].' / '.$aVinRequest['year']);
			$aField['volume']=array('title'=>'Volume','type'=>'text','value'=>$aVinRequest['volume']);
			$aField['body']=array('title'=>'Body','type'=>'text','value'=>$aVinRequest['body']);
			$aField['kpp']=array('title'=>'KPP','type'=>'text','value'=>$aVinRequest['kpp']);
			if($aVinRequest['wheel']) $aField['wheel']=array('title'=>'Wheel','type'=>'text','value'=>$aVinRequest['wheel']);
			if($aVinRequest['utable']) $aField['utable']=array('title'=>'VinUtable','type'=>'text','value'=>$aVinRequest['utable']);
			if($aVinRequest['engine_number']) $aField['engine_number']=array('title'=>'VinEngineNumber','type'=>'text','value'=>$aVinRequest['engine_number']);
			if($aVinRequest['engine_code']) $aField['engine_code']=array('title'=>'engine_code','type'=>'text','value'=>$aVinRequest['engine_code']);
			if($aVinRequest['engine_volume']) $aField['engine_volume']=array('title'=>'engine_volume','type'=>'text','value'=>$aVinRequest['engine_volume']);
			if($aVinRequest['kpp_number']) $aField['kpp_number']=array('title'=>'kpp_number','type'=>'text','value'=>$aVinRequest['kpp_number']);
			$aField['additional']=array('title'=>'Additional','type'=>'text','value'=>$aVinRequest['additional']);
			$aField['customer_comment']=array('title'=>'Customer Comment','type'=>'text','value'=>$aVinRequest['customer_comment']);
			require_once(SERVER_PATH.'/class/system/Language.php');
			$aField['customer_info']=array('title'=>'Customer Info','type'=>'text','value'=>Language::AddOldParser('customer',$aVinRequest['id_user']));
			$aField['hr']=array('type'=>'hr','colspan'=>2);
			$aField['order_status']=array('title'=>'Old Order Status','type'=>'text','value'=>Language::GetMessage($aVinRequest['order_status']));
			$aField['old_order_status']=array('type'=>'hidden','name'=>'old_order_status','value'=>$aVinRequest['order_status']);
			$aField['manager_comment']=array('title'=>'Comment','type'=>'textarea','name'=>'manager_comment','value'=>$aVinRequest['manager_comment']);
			$aField['is_remember']=array('title'=>'Remember Text','type'=>'checkbox','value'=>1,'onclick'=>"xajax_process_browse_url('?action=manager_vin_request_remember&id=".$aVinRequest['id']."&checked='+this.checked);",
			    'checked'=>$aVinRequest['is_remember'],'add_to_td'=>array('remember_text'=>array('type'=>'textarea','name'=>'remember_text','value'=>$aVinRequest['remember_text'])));
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"VIN Request Preview",
			'sAdditionalTitle'=>" # ".Base::$aRequest['id'],
			//'sContent'=>Base::$tpl->fetch('manager/form_vin_request.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'bShowBottomForm'=>false,
			'sError'=>$sError,
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();

			$aPartList=unserialize($aVinRequest['part_array']);
			if ($aPartList) foreach ($aPartList as $key => $value)
			$aPartList[$key]['name']=base64_decode($value[name]);

			Base::$tpl->assign('aPartList',$aPartList);
			if ($aPartList) {
				foreach ($aPartList as $value) {
					$dSubtotal+=floatval($value['number'])*floatval($value['price']);
				}
			}
			Base::$tpl->assign('dSubtotal',$dSubtotal);
			Base::$tpl->assign('iRowCount',count($aPartList));

			Base::$tpl->assign('aManagerLogin',  Base::$db->GetAssoc(Base::GetSql('Manager/LoginAssoc')) );

			Base::$sText.=Base::$tpl->fetch('manager/form_vin_request_part_list.tpl');
			return;
		}

		// ######### List #########
		$sSql="select u.*,uc.* from user u
			inner join user_customer uc on u.id=uc.id_user
			 where 1=1
			 	and u.id in (".$this->sCustomerSql.")";
		Base::$tpl->assign('aCustomer',Base::$db->getAll($sSql));

		$aOrderStatus=array(
		    ''=>Language::GetMessage('All'),
		    'new'=>Language::GetMessage('new'),
		    'work'=>Language::GetMessage('work'),
		    'refused'=>Language::GetMessage('refused'),
		    'parsed'=>Language::GetMessage('parsed'),
		);
		
		$aField['login']=array('title'=>'Customer','type'=>'input','value'=>Base::$aRequest['search']['login'],'name'=>'search[login]');
		$aField['id']=array('title'=>'Request #','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>Base::$aRequest['search']['phone'],'name'=>'search[phone]');
		$aField['is_remember']=array('title'=>'Only Is Remember','type'=>'checkbox','name'=>'search[is_remember]','value'=>'1','checked'=>Base::$aRequest['search']['is_remember']);
		$aField['order_status']=array('title'=>'Status','type'=>'select','options'=>$aOrderStatus,'name'=>'search[order_status]','selected'=>Base::$aRequest['search']['order_status']);
		$aField['marka']=array('title'=>'Marka','type'=>'input','value'=>Base::$aRequest['search']['marka'],'name'=>'search[marka]');
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sTitle'=>"Search vin requests",
		//'sContent'=>Base::$tpl->fetch('manager/form_vin_request_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_vin_request',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();

		// --- search ---
		if (Base::$aRequest['search']['id']) $sWhere.=" and vr.id = '".Base::$aRequest['search']['id']."'";
		if (Base::$aRequest['search']['login']) $sWhere.=" and u.login ='".Base::$aRequest['search']['login']."'";
		if (Base::$aRequest['search']['is_remember']) $sWhere.=" and vr.is_remember ='1'";
		if (Base::$aRequest['search']['phone']) $sWhere.=" and uc.phone like '%".Base::$aRequest['search']['phone']."%'";
		if (Base::$aRequest['search']['email']) $sWhere.=" and u.email like '%".Base::$aRequest['search']['email']."%'";
		if (Base::$aRequest['search']['order_status']) $sWhere.=" and vr.order_status = '"
		.Base::$aRequest['search']['order_status']."'";
		if (Base::$aRequest['search']['marka']) $sWhere.=" and vr.marka = '".Base::$aRequest['search']['marka']."'
			and vr.order_status!='new'";
		// --------------

		$oTable=new Table();
		$oTable->sSql="select uc.*, cg.*,u.*,uc.*,u.login, uc.name as customer_name
					, m.login as manager_login
					, vr.*
					from vin_request vr
				inner join user u on vr.id_user=u.id
				inner join user_customer uc on uc.id_user=u.id
				inner join customer_group cg on uc.id_customer_group=cg.id
				inner join user_account ua on ua.id_user=u.id
				inner join user m on uc.id_manager=m.id
			where vr.id_user=u.id
				".$sWhere;

		$oTable->aOrdered="order by vr.post_date desc";
		$oTable->iRowPerPage=20;
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'#'),
		'order_status'=>array('sTitle'=>'Order Status'),
		'id_user'=>array('sTitle'=>'Customer/Phone'),
		'vin'=>array('sTitle'=>'VIN'),
		'post'=>array('sTitle'=>'Post'),
		'order_status'=>array('sTitle'=>'Status'),
		'marka'=>array('sTitle'=>'Marka'),
		'manager_comment'=>array('sTitle'=>'Manager Comment/Remember'),
		'action'=>array(),
		);
		$oTable->aCallback=array($this,'CallParseVinRequest');
		$oTable->sDataTemplate='manager/row_vin_request.tpl';

		Base::$sText.=$oTable->getTable("Vin requests from customers");
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseVinRequest(&$aItem)
	{
		//require_once(SERVER_PATH.'/class/core/DateFormat.php');
		require_once(SERVER_PATH.'/class/core/String.php');
		require_once(SERVER_PATH.'/class/system/Currency.php');

		if ($aItem) {
			foreach($aItem as $key => $value) {
				$aOrderId[]=$value['id'];
				$aItem[$key]['discount']=max(array($value['discount_static']
				, $value['discount_dynamic'], $value['group_discount']));
				$aItem[$key]['debt']=Currency::PrintPrice(
				max(array($value['user_debt'], $value['group_debt'])),$value['code_currency']);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequestSave($bRedirect=true)
	{
		if (Base::$aRequest['is_post']) {
			$aUserInputCode = array();

			if (Base::$aRequest['data']['change_login'] && Base::$aRequest['data']['current_login']) {
				Db::Execute("update user inner join user_customer on user.id=user_customer.id_user
				set user.login='".Base::$aRequest['data']['change_login']."'
				, user.email='".Base::$aRequest['data']['email']."'
				, user_customer.phone='".Base::$aRequest['data']['phone']."'
				where user.login='".Base::$aRequest['data']['current_login']."'");
			} else {
				Db::Execute(" update user inner join user_customer on user.id=user_customer.id_user
				set user.email='".Base::$aRequest['data']['email']."'
				, user_customer.phone='".Base::$aRequest['data']['phone']."'
				where user.login='".Base::$aRequest['data']['current_login']."'"
				);
			}




			//[----- UPDATE -----------------------------------------------------]
			if (Base::$aRequest['part']) {
				require_once(SERVER_PATH.'/class/module/Catalog.php');
				$j = 0;
				foreach(Base::$aRequest['part'] as $value) {
					++ $j;
					if(
					$value['user_input_code'] &&
					(strripos($value['code'], "ZZZ_") !== false)
					)
					{
						$aUserInputCode[$j] = $value['user_input_code'];
					} else {
						$aUserInputCode[$j] = $value['code'];
					}
					$aCode[]="'" . Catalog::StripCode( $value['code'] ) . "'";
				}
				$aCrosCode=Base::$db->GetAll("select * from cat_part where code in (".implode(',',$aCode).")");
				$aCrosHash=Language::Array2Hash($aCrosCode,'code');
			}

			for ($i=1;$i<=100;$i++) {
				if (Base::$aRequest['part'][$i]) {

					if (Base::$aRequest['part'][$i]['number']<=0) Base::$aRequest['part'][$i]['number']=1;
					require_once(SERVER_PATH.'/class/system/Discount.php');

					if ($aCrosHash[Base::$aRequest['part'][$i]['code']]['id']
					&& Base::$db->GetRow(Base::GetSql('Price/Search',array('sCode'=>"'".Base::$aRequest['part'][$i]['code']."'"
					, 'sItemCode'=>"''"	, 'price_type'=>Auth::$aUser['price_type']
					, 'customer_margin'=>Auth::$aUser['customer_group_margin']+Auth::$aUser['parent_margin']
					, 'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser)))))
					{
						$sCode = 'zzz_' . $aCrosHash[ Base::$aRequest['part'][$i]['code'] ]['id'];
					} else {
						require_once(SERVER_PATH.'/class/module/Catalog.php');
						$sCode = Catalog::StripCode( Base::$aRequest['part'][$i]['code'] );
					}

					$aPartList[] = array(
					'i'=>$i,
					'name'=>base64_encode(Base::$aRequest['part'][$i]['name']),
					'marka'=>Base::$aRequest['part'][$i]['marka'],
					'code'=> $sCode,
					'user_input_code' => $aUserInputCode[$i],
					'cat_name'=>Base::$aRequest['part'][$i]['cat_name'],
					'code_visible'=>Base::$aRequest['part'][$i]['code_visible'],
					'i_visible'=>Base::$aRequest['part'][$i]['i'],
					'number'=>Base::$aRequest['part'][$i]['number'],
					'price'=>Base::$aRequest['part'][$i]['price'],
					'price_original'=>Base::$aRequest['part'][$i]['price_original'],
					'term'=>Base::$aRequest['part'][$i]['term'],
					'id_provider'=>Base::$aRequest['part'][$i]['id_provider'],
					'provider'=> $aProviderHash[Base::$aRequest['part'][$i]['id_provider']]['name'],
					'code_delivery'=> $aProviderHash[Base::$aRequest['part'][$i]['id_provider']]['code_delivery'],
					'weight'=>Base::$aRequest['part'][$i]['weight'],
					);
				}
			}
			$sPartArray=serialize($aPartList);

			Base::$db->Execute("update vin_request set
						part_array='$sPartArray',
						manager_comment= '".Base::$aRequest['manager_comment']."',
						remember_text= '".Base::$aRequest['remember_text']."'
					where id='".Base::$aRequest['id']."'
						and id in (".$this->GetVinIdList(true).") ");
			//[----- END UPDATE -------------------------------------------------]
		}
		if ($bRedirect) Base::Redirect('/?action=manager_vin_request_edit&form_message=saved&id='.Base::$aRequest['id']);
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequestSend()
	{
		if (Base::$aRequest['is_post']) {
			$this->VinRequestSave(false);

			$aVinRequest=Base::$db->getRow(Base::GetSql('VinRequest',array(
			'id'=>Base::$aRequest['id'],
			'id_in'=>$this->GetVinIdList(),
			)));
			if (!$aVinRequest) Base::Redirect('/?action=manager_vin_request');

			$aPartList=unserialize($aVinRequest['part_array']);
			if ($aPartList) foreach ($aPartList as $key => $value)
			$aPartList[$key]['name']=base64_decode($value[name]);
			$aVinRequest['part_list']=$aPartList;

			Base::$db->Execute("update vin_request set order_status='parsed' where
				order_status in ('work','refused')
				and id='".Base::$aRequest['id']."'
				and id in (".$this->GetVinIdList(true).") ");

			$this->VinRequestRelease(Base::$aRequest['id']);

			if ($aVinRequest['mobile']) {
				//$this->VinRequestMobileNotification($aVinRequest);
			}

			if (Base::$aRequest['section']=='customer') {
				//				if (Customer::IsChangeableLogin($aVinRequest['login'])) {
				//					Message::CreateDelayedNotification($aVinRequest['id_user'], 'vin_request_sent_password'
				//					,array('aVinRequest'=>$aVinRequest),true);
				//				}
				//				else {
				Message::CreateDelayedNotification($aVinRequest['id_user'], 'vin_request_sent'
				,array('aVinRequest'=>$aVinRequest),true);
				//}
			}
		}
		Base::Redirect('/?action=manager_vin_request');
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequestRefuse()
	{
		if (Base::$aRequest['is_post']) {
			$aVinRequest=Base::$db->getRow(Base::GetSql('VinRequest',array(
			'id'=>Base::$aRequest['id'],
			'id_in'=>$this->GetVinIdList(),
			)));
			if (!$aVinRequest) Base::Redirect('/?action=manager_vin_request');

			Base::$db->Execute("update vin_request set order_status='refused' where id='".Base::$aRequest['id']."'");
			$this->VinRequestRelease(Base::$aRequest['id']);

			require_once(SERVER_PATH.'/class/module/Message.php');
			Message::CreateDelayedNotification($aVinRequest['id_user'], 'vin_request_refused' ,$aVinRequest);

			if ($aVinRequest['mobile']) {
				$this->VinRequestMobileNotification($aVinRequest);
			}
		}
		Base::Redirect('/?action=manager_vin_request');
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Makes manager able to take new vin request from general queue
	 */
	public function VinRequestRelease($iId)
	{
		Base::$db->Execute("update user_manager set id_vin_request_fixed='0'
				where id_user='".Auth::$aUser['id']."' and id_vin_request_fixed='$iId'");
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequestMobileNotification($aVinRequest)
	{
		require_once(SERVER_PATH.'/class/system/Content.php');
		$aCustomer=Base::$db->GetRow( Base::GetSql('Customer',array('id'=>$aVinRequest['id_user'])) );
		$sMessage=strip_tags(String::GetSmartyTemplate('parsed_vin_request',array(
		'vin_request'=>$aVinRequest,
		'user'=>$aCustomer,
		)));
		require_once(SERVER_PATH.'/class/core/Sms.php');
		Sms::AddDelayed($aVinRequest['mobile'],$sMessage);

		require_once(SERVER_PATH.'/class/module/Message.php');
		$sNoteDescription=String::GetSmartyTemplate('vin_request_mobile_parsed');
		Message::AddNote($aVinRequest['id_user'], Language::GetMessage('Vin request mobile parsed Subject')
		,$sNoteDescription);
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Get the list of id vinrequests which manager can have access
	 *
	 * @return array
	 */
	public function GetVinIdList($bReturnArray=false)
	{
		$sVinRequestQueue=Base::GetSql('VinRequest/MyQueue',array(
		'id_manager'=>Auth::$aUser['id'],
		'view_all'=>(Auth::$aUser['is_super_manager'] || Auth::$aUser['is_sub_manager'] ? "1":"") ,
		'assoc'=>($bReturnArray ? "1":"") ,
		));
		if ($bReturnArray) {
			return implode(',',Base::$db->GetAssoc($sVinRequestQueue));
		}
		return $sVinRequestQueue;
	}
	//-----------------------------------------------------------------------------------------------
	public function VinRequestRemember()
	{
		if (Base::$aRequest['id']) {
			$aVinRequest['is_remember']=(Base::$aRequest['checked']=='true' ? 1:0);
			Base::$db->AutoExecute('vin_request',$aVinRequest,'UPDATE',"id='".Base::$aRequest['id']."'");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PackageAddOrderItem() {
	    if(!Base::$aRequest['zzz_code'] || !Base::$aRequest['id_cart_package']) {
	        //error
	        Base::Redirect("/?".urldecode(Base::$aRequest['return'])."&aMessage[MT_ERROR]=Ошибка добавления товара к заказу");
	    } else {
	        //all ok
	    	$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cart_package'])));
			if ($aCartPackage)	    	
		    	$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
		    			'id'=>($aCartPackage['id_user'] ? $aCartPackage['id_user'] : -1),
		    	)));
			if (!$aCustomer)
				$aCustomer = Auth::$aUser;
				
	        $a=Db::GetRow(Base::GetSql('Catalog/Price',array(
	           'is_not_check_item_code' => 1,
	           'where'=>" and p.id='".str_replace("ZZZ_", '', Base::$aRequest['zzz_code'])."' ",
	           'customer_discount'=>Discount::CustomerDiscount($aCustomer)
	        )));
	        if($a) {
	            if(!Base::$aRequest['number']) Base::$aRequest['number']=1;
	            $a['type_']='order';
	            $a['id_cart_package']=$aCartPackage['id'];
    	        $a['zzz_code'] = $a['id'];
    	        $a['id_currency_user']=(Auth::$aUser['id_currency']?Auth::$aUser['id_currency']:1);
    	        $a['price_currency_user'] = Currency::PrintPrice($a['price'],Auth::$aUser['id_currency'],2,"<none>")*Base::$aRequest['number'];
    	        
    	        if($aCartPackage['order_status']=='pending') $a['order_status']='pending';
    	        else $a['order_status']='new';
    	        
    	        unset($a['id']);
    	        unset($a['post_date']);
    	        $a['id_user']=$aCartPackage['id_user'];
    	        $a['session']=session_id();
    	        $a['number']=Base::$aRequest['number'];
    	        $a['customer_id']='';
    	        // TODO: what this? - maybe $aCustomer['parent_margin'] ???
    	        $a['price_parent_margin']=$a['price_original']*Auth::$aUser['parent_margin']/100;
    	        $a['price_parent_margin_second']=$a['price_original']*Auth::$aUser['parent_margin_second']/100;
    	        
    	        $a['id_provider_ordered']=$a['id_provider'];
    	        $a['provider_name_ordered']=$a['provider_name'];

        		Db::AutoExecute("cart", $a);
        		$iIdCart = Db::InsertId();
        		
        		DB::Execute("insert into cart_log (id_cart, post, order_status, comment, id_user_manager)
					values (".$iIdCart.", UNIX_TIMESTAMP(), 'add_order', '".Language::getMessage('manager_add_item_to_order')."', ".Auth::$aUser['id'].")");
        		
        		//recalc order
        		$aData['id_cart_package']=$aCartPackage['id'];
        		$this->SetPriceTotalCartPackage($aData);
        		
        		// change balance user
        		$iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aCartPackage['id']." and operation='pending_work'");
        		if ($iWorkPayAlready)
        			Finance::Deposit($aCartPackage['id_user'],-(Currency::PrintPrice($a['price'],null,0,'<none>')*$a['number']),Language::getMessage('manager_add_item_to_order').': '.$iIdCart
    					,$aCartPackage['id'],'internal','cart',0,6,0,'',0,0,true,0,'','',0,$iIdCart);
        		
        		$sSubject = 'Manager add product in order';
        		$a['id_cart_package'] = $aCartPackage['id'];
        		$a['date'] = DateFormat::getDateTime(time());
        		
        		$aCartCustomer=Db::GetRow(Base::GetSql('Customer',array(
        			'id'=>$aCartPackage['id_user'],
        		)));
        		$aCartManager=Db::GetRow(Base::GetSql('Manager',array(
        				'id'=>$aCartCustomer['id_manager'],
        		)));
        		Message::CreateDelayedNotification($aCartCustomer['id_user'], 'manager_add_new_product'
        		,array('aCart'=>$a, 'info' => $aCartCustomer, 'aManager' => $aCartManager),true,$iIdCart);
        		
        		/*if (Base::GetConstant("manager:enable_create_provider_requests","1"))
        			ProviderRequests::CreateRequest($aCartPackage['id']);*/
        		
        		Base::Redirect("/?".urldecode(Base::$aRequest['return'])."&aMessage[MT_NOTICE]=Товар добавлен к заказу успешно");
	        } else {
	            Base::Redirect("/?".urldecode(Base::$aRequest['return'])."&aMessage[MT_ERROR]=Ошибка! Нет такого товара в прайсе");
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function Package() {
		Base::Message();
		$this->sPreffixAction="manager_package_list";
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'package_list');

		if(Auth::$aUser['is_super_manager'])
		    $sWhereManager = ' ';
		else
		    $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
		
		/* [ apply  */
		if (Base::$aRequest['is_post'])
		{
			//if (!Base::$aRequest['data']['id_user'] || !Base::$aRequest['data']['id_user_provider'])
			if (0)
			{
				Base::Message(array('MF_ERROR'=>'Required fields user and provider'));
				Base::$aRequest['action']=$this->sPreffix.'_add';
				Base::$tpl->assign('aData', Base::$aRequest['data']);
			}
			else
			{
				$aData=String::FilterRequestData(Base::$aRequest['data']);

				//$aData["date_accept"]=DateFormat::FormatSearch($aData["date_accept"]);

				if (Base::$aRequest['id']) {
					$aCartPackageOld=Db::getRow("Select cp.* from cart_package cp
						where id=".Base::$aRequest['id']);
					
					// AOT-42
					if (Auth::$aUser['is_super_manager']) {
						if (Base::$aRequest['data']['id_manager']) {
							$aManager = Db::GetRow(Base::GetSql('Manager',array(
								'id'=>Base::$aRequest['data']['id_manager'],
							)));
							if ($aManager)
								Db::Execute("Update user_customer set id_manager=".$aManager['id_user']." where id_user=".$aCartPackageOld['id_user']);
						}	
					}
					
					$aData['delivery_link']=str_replace("&amp;","&",$aData['delivery_link']);

					Db::AutoExecute("cart_package",$aData,"UPDATE","id=".Base::$aRequest['id']);
					$sMessage="&aMessage[MT_NOTICE]=Package updated";

					Db::Execute(" update user
					inner join user_customer on user.id=user_customer.id_user
					set user.email='".$aData['email']."'
					, user_customer.name='".$aData['name']."'
					, user_customer.address='".$aData['address']."'
					, user_customer.zip='".$aData['zip']."'
					, user_customer.city='".$aData['city']."'
					, user_customer.phone='".$aData['phone']."'
					, user_customer.phone2='".$aData['phone2']."'
					, user_customer.country='".$aData['country']."'
					, user_customer.remark='".$aData['remark']."'
					, id_user_customer_type='".$aData['id_user_customer_type']."'
					, entity_type='".$aData['entity_type']."'
					, entity_name='".$aData['entity_name']."'
					, additional_field1='".$aData['additional_field1']."'
					, additional_field2='".$aData['additional_field2']."'
					, additional_field3='".$aData['additional_field3']."'
					, additional_field4='".$aData['additional_field4']."'
					, additional_field5='".$aData['additional_field5']."'
					where user.login='".$aData['login']."'
					");

					$aData['id_cart_package']=Base::$aRequest['id'];
					$this->SetPriceTotalCartPackage($aData);
					
					$sUserLogin = Base::$aRequest['data']['login'];
					if (Base::$aRequest['data']['change_login'] && Base::$aRequest['data']['login']) {
						$sUserLogin = Base::$aRequest['data']['change_login'];
						Db::Execute("update user 
            				set user.login='".Base::$aRequest['data']['change_login']."',
					        is_temp=0
            				where user.login='".Base::$aRequest['data']['login']."'");
					}
							
					$aCartPackageNew=Db::getRow("Select * from cart_package where id=".Base::$aRequest['id']);
					if ($aCartPackageNew['order_status']=="pending")
					{ } // не было движений
					else {							
						// recalc delivery - if order pay
						if ($aCartPackageOld['price_delivery']!=$aCartPackageNew['price_delivery']) {
							$dAmountDelivery = $aCartPackageNew['price_delivery']-$aCartPackageOld['price_delivery'];
							// вернуть клиенту
							if ($dAmountDelivery < 0)
								Finance::Deposit($aCartPackageNew['id_user'],abs($dAmountDelivery),
								Language::getMessage('return money for delivery'),
								$aCartPackageNew['id'],'internal','cart','',5);
							else { // оплата доставки
								$aOperation = Db::GetRow("Select * from user_account_type_operation where code='pay_delivery'");
								Finance::Deposit($aCartPackageNew['id_user'],$dAmountDelivery,
									$aOperation['name'],$aCartPackageNew['id'],'interval','',0,0,0,
									$aOperation['code'],0,0,true,0);
							}						
						}
					}
				}
				else
				{
					//Db::AutoExecute("package",$aData);
					$sMessage="&aMessage[MF_ERROR]=Faild";
				}
				Form::RedirectAuto($sMessage);
			}
		}
		/* ] apply */

		//--------------------------------------------------------------------------
		if (Base::$aRequest['action']=='manager_package_order') {
			//if (!Auth::$aUser['is_super_manager']) Base::Redirect('/?action=auth_type_error');
			//require_once(SERVER_PATH.'/class/module/Finance.php');

			$aUserCart=Base::$db->getAll("select * from cart where id_cart_package='".Base::$aRequest['id']."'
				and order_status='pending'
				and type_='order'
				");
			//and id_user in (".$this->sCustomerSql.")
			if (!$aUserCart) Base::Redirect('?action=manager_package_list&table_error=cart_package_not_found');
			else {
				$iIdUser=$aUserCart[0]['id_user'];
				$aUserCartId=array();
				foreach ($aUserCart as $aValue) {
					$dPriceTotal+=$aValue['price']*$aValue['number'];
					$aUserCartId[]=$aValue['id'];
				}
			}

			//if (!Finance::HaveMoney($dPriceTotal,$iIdUser))
			//Base::Redirect('/?action=manager_package_list&table_error=not_enough_money');

			require_once(SERVER_PATH.'/class/module/Cart.php');
			Cart::SendPendingWork(Base::$aRequest['id']);

			Base::Redirect("/?action=manager_package_list");
		}
		//--------------------------------------------------------------------------

		if (Base::$aRequest['action']=='manager_package_edit') {
			if (Base::$aRequest['action']=='manager_package_edit') {
				//$aCartPackage=Base::$db->getRow("select * from cart_package where id='".Base::$aRequest['id']."'
				//			and id_user in (".$this->sCustomerSql.")");

			    if (Base::$aRequest['id']) {
			        Base::$db->Execute("update cart_package set is_viewed='1' where id='".Base::$aRequest['id']."' ");
			        $sPrintPac="/?action=manager_order_print&id=".Base::$aRequest['id']."&id_user=".Db::GetOne("SELECT id_user FROM `cart_package` where id=".Base::$aRequest['id']);
			        $sPrintPac="<a class=btn style='float: right; margin: -40px 5px' href='".$sPrintPac."' target='_blank'>
			         <img src='/image/fileprint.png' border='0' width='16' align='absmiddle' hspace='1/'>Печать заказа</a> ";
// 		    	    $sPrintPac.='<span style="float: right;"><a href="/?action=cart_create&id_cart_package='.Base::$aRequest['id'].'" style=""><img src="/image/plus.png" border="0" width="16" align="absmiddle">Создать позицию</a>&nbsp;&nbsp;&nbsp;</span>';
			    }
			    
				Base::$tpl->assign('sAutoInfo',$sAutoInfo=OwnAuto::GetAutoInfoTip(Base::$aRequest['id']));
				Base::$tpl->assign('aDeliveryType',$aDeliveryType=array(""=>"")+Db::GetAssoc("Assoc/DeliveryType"));
				Base::$tpl->assign('aPaymentType',$aPaymentType=Db::GetAssoc("select pt.id, pt.name from payment_type pt where 1=1 and pt.visible=1  order by pt.num"));

				$aCartPackage=Db::GetRow(Base::GetSql("CartPackage", array("id"=>Base::$aRequest['id'])));
				if ($aCartPackage) {
					$aCartCustomer=Db::GetRow(Base::GetSql('Customer',array(
						'id'=>$aCartPackage['id_user'],
					)));
					Base::$db->Execute("update cart_package set is_viewed='1' where id='".Base::$aRequest['id']."' ");
					Base::$tpl->assign('aUser',$aCartPackage);
					Base::$tpl->assign('aData',$aCartPackage);
	
					$oBuh=new Buh();
					$aPayment=$oBuh->GetAmount("cart_package",$aCartPackage['id'],361);
					Base::$tpl->assign('aPayment',$aPayment);
				}
				if ($aPayment['id_buh_debit_subconto1']) {
					$aAccount=Db::GetRow(Base::GetSql("Account", array("id"=>$aPayment['id_buh_debit_subconto1'])));
					Base::$tpl->assign('aAccount',$aAccount);
				}

				$oContent = new Content();
				Base::$tpl->assign('oContent',$oContent);
			}
			
			Base::$tpl->assign('aUserCustomerType',$aUserCustomerType=array(
			    '1'=>Language::GetMessage('частное лицо'),
			    '2'=>Language::GetMessage('юридическое лицо')
			));
			$aEntityType=explode(",",Language::GetConstant('user:entity_type', 'ООО,ЗАО,ОАО,АО,ЧП,ИЧП,ИЧП,ТОО,ИНОЕ'));
			Base::$tpl->assign('aEntityType',$aEntityType);
            
			foreach ($aEntityType as $aValue){
			    $aEntityTypeOptions[$aValue]=$aValue;
			}
			
			Resource::Get()->Add('/js/user.js',2);

			if (Auth::$aUser['is_super_manager']) {
				$aManager=Db::GetAssoc("select u.id, concat(um.name,' ( ',u.login,' )') name
					from user_manager um,user u
					where u.id=um.id_user and ((u.visible=1 and um.has_customer=1) or u.id=".
						$aCartPackage['id_manager'].")  order by um.name,u.login");
				if ($aManager)
					$aField['manager']=array('title'=>'Manager','type'=>'select','options'=>$aManager,
						'selected'=>($aCartPackage['id_manager'] ? $aCartPackage['id_manager'] : $aCartCustomer['id_manager']),'name'=>'data[id_manager]',
						'tr_id'=>'manager_tr_id');
			}
			
			if($oContent->IsChangeableLogin($aCartPackage['login'])){
			    $aField['change_login']=array('title'=>'Login','type'=>'input','value'=>'m'.$aCartPackage['login'],'name'=>'data[change_login]','readonly'=>1);
			    $aField['login_hidden']=array('type'=>'hidden','name'=>'data[login]','value'=>$aCartPackage['login'],'readonly'=>1);
			}else
			    $aField['login']=array('title'=>'Login','type'=>'input','value'=>$aCartPackage['login'],'name'=>'data[login]','readonly'=>1);
			$aField['email']=array('title'=>'email','type'=>'input','value'=>$aCartPackage['email'],'name'=>'data[email]');
			$aField['delivery_info']=array('type'=>'text','value'=>Language::GetMessage('Delivery info'),'colspan'=>2);
			$aField['hr']=array('type'=>'hr','colspan'=>2);
			$aField['id_user_customer_type']=array('title'=>'User customer type','type'=>'select','options'=>$aUserCustomerType,'selected'=>($aCartPackage['id_user_customer_type']!='')?$aCartPackage['id_user_customer_type']:Base::$aRequest['data']['id_user_customer_type'],
			    'name'=>'data[id_user_customer_type]','id'=>'user_customer_type_id','onchange'=>"oUser.ToggleEntityTr($('#user_customer_type_id').val())");
			$aField['entity_type']=array('title'=>'Entity name','type'=>'select','options'=>$aEntityTypeOptions,'selected'=>($aCartPackage['entity_type']!='')?$aCartPackage['entity_type']:Base::$aRequest['data']['entity_type'],'name'=>'data[entity_type]','tr_id'=>'entity_tr_id','add_to_td'=>array(
			    'entity_name'=>array('type'=>'input','value'=>$aCartPackage['entity_name']?$aCartPackage['entity_name']:Base::$aRequest['data']['entity_name'],'name'=>'data[entity_name]','tr_class'=>'entity_tr_id')
			));
			$aField['additional_field1']=array('title'=>'additional_field1','type'=>'input','value'=>$aCartPackage['additional_field1']?$aCartPackage['additional_field1']:Base::$aRequest['data']['additional_field1'],'name'=>'data[additional_field1]','tr_id'=>'additional_field1_tr_id');
			$aField['additional_field2']=array('title'=>'additional_field2','type'=>'input','value'=>$aCartPackage['additional_field2']?$aCartPackage['additional_field2']:Base::$aRequest['data']['additional_field2'],'name'=>'data[additional_field2]','tr_id'=>'additional_field2_tr_id');
			$aField['additional_field3']=array('title'=>'additional_field3','type'=>'input','value'=>$aCartPackage['additional_field3']?$aCartPackage['additional_field3']:Base::$aRequest['data']['additional_field3'],'name'=>'data[additional_field3]','tr_id'=>'additional_field3_tr_id');
			$aField['additional_field4']=array('title'=>'additional_field4','type'=>'input','value'=>$aCartPackage['additional_field4']?$aCartPackage['additional_field4']:Base::$aRequest['data']['additional_field4'],'name'=>'data[additional_field4]','tr_id'=>'additional_field4_tr_id');
			$aField['additional_field5']=array('title'=>'additional_field5','type'=>'input','value'=>$aCartPackage['additional_field5']?$aCartPackage['additional_field5']:Base::$aRequest['data']['additional_field5'],'name'=>'data[additional_field5]','tr_id'=>'additional_field5_tr_id');
			$aField['name']=array('title'=>'FLName','type'=>'input','value'=>$aCartPackage['name']?$aCartPackage['name']:Base::$aRequest['data']['name'],'name'=>'data[name]','szir'=>1);
			$aField['city']=array('title'=>'City','type'=>'input','value'=>$aCartPackage['city']?$aCartPackage['city']:Base::$aRequest['data']['city'],'name'=>'data[city]','szir'=>1);
			$aField['address']=array('title'=>'Address','type'=>'input','value'=>$aCartPackage['address']?$aCartPackage['address']:Base::$aRequest['data']['address'],'name'=>'data[address]','szir'=>1);
			$aField['phone']=array('title'=>'Phone','type'=>'input','value'=>$aCartPackage['phone']?$aCartPackage['phone']:Base::$aRequest['data']['phone'],'name'=>'data[phone]','id'=>'user_phone','placeholder'=>'(___)___ __ __','szir'=>1);
			$aField['remark']=array('title'=>'Remarks','type'=>'textarea','name'=>'data[remark]','value'=>$aCartPackage['remark']?$aCartPackage['remark']:Base::$aRequest['data']['remark']);
			if($aCartPackage['is_need_check']){
			    $aField['checked_auto']=array('type'=>'span','id'=>'auto_'.$aCartPackage['id'],'onclick'=>$aCartPackage['is_checked_auto']?"set_checked_auto(this,0)":"set_checked_auto(this,1)",
			        'onmouseout'=>"$('#tip_auto_".$aCartPackage['id']."').hide();",'onmouseover'=>"$('#tip_auto_".$aCartPackage['id']."').show();",'value'=>($aCartPackage['is_checked_auto']==0)?'<a><img src="/image/design/not_sel_chk.png"></img></a>':'<a><img src="/image/design/sel_chk.png"></img></a>');
			    $aField['auto_info']=array('type'=>'span','class'=>'tip_div','style'=>'display:none;width:500px;','id'=>'tip_auto_'.$aCartPackage['id'],'value'=>$sAutoInfo);
			}
			if($aCartPackage['id_user_customer_type']!=''){
			    if($aCartPackage['id_user_customer_type']==1)
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
			
			//right form
			$aField['id_delivery_type']=array('title'=>'delivery type','type'=>'select','options'=>$aDeliveryType,'selected'=>$aCartPackage['id_delivery_type'],'name'=>'data[id_delivery_type]');
			$aField['id_payment_type']=array('title'=>'payment type','type'=>'select','options'=>$aPaymentType,'selected'=>$aCartPackage['id_payment_type'],'name'=>'data[id_payment_type]');
			$aField['price_delivery']=array('title'=>'Sum delivery','type'=>'input','value'=>htmlentities($aCartPackage['price_delivery'],ENT_QUOTES,'UTF-8'),'name'=>'data[price_delivery]');
			$aField['price_total']=array('title'=>'Sum total','type'=>'input','value'=>htmlentities($aCartPackage['price_total'],ENT_QUOTES,'UTF-8'),'name'=>'data[price_total]');
			$aField['customer_comment']=array('title'=>'Customer<br>Comment','type'=>'textarea','name'=>'data[customer_comment]','value'=>htmlentities($aCartPackage['customer_comment'],ENT_QUOTES,'UTF-8'));
			$aField['manager_comment']=array('title'=>'Manager<br>Comment<br>invisble','type'=>'textarea','name'=>'data[manager_comment]','value'=>htmlentities($aCartPackage['manager_comment'],ENT_QUOTES,'UTF-8'));
			if($aCartPackage['user_contact_address']){
			    $aField['user_contact_address']=array('title'=>'Additional<br>Address','type'=>'input','value'=>htmlentities($aCartPackage['user_contact_address'],ENT_QUOTES,'UTF-8'),'name'=>'data[user_contact_address]','readonly'=>1);
			}
			
			$oForm=new Form();
			$oForm->sHeader="method=post";
			$oForm->sTitle="Cart Package";
			$oForm->sAdditionalTitle=" ".Base::$aRequest['id'];
			//$oForm->sContent=Base::$tpl->fetch($this->sPrefix.'/form_package.tpl');
			$oForm->aField=$aField;
			$oForm->bType='generate';
			//$oForm->sRightTemplate=$this->sPrefix.'/right_form_package.tpl';
			$oForm->sSubmitButton='Apply';
			$oForm->sSubmitAction=$this->sPreffixAction;
			$oForm->sReturnButton='<< Return';
			$oForm->bAutoReturn=true;
			$oForm->sReturn=Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']);
			$oForm->sWidth="380px";

			$isDisableEditOrder = 0;
			$aCart = Db::getAll("Select * from cart where id_cart_package=".$aCartPackage['id']);
			if ($aCart)
				foreach ($aCart as $aItem)
					if ($aItem['order_status']!='new')
						$isDisableEditOrder = 1;
			if (Auth::$aUser['is_super_manager'])
			{}
			elseif ($isDisableEditOrder || $aCartPackage['order_status']!='work')
				$oForm->sSubmitActionDisable=true;

			Base::$sText.=$oForm->getForm();

			$oTable=new Table();

			$oTable->sSql=Base::GetSql("Part/Search",array(
			"id_cart_package"=>Base::$aRequest['id'],
			));

			$oTable->aOrdered="order by c.post desc";
			$oTable->aCallback=array($this,'CallParseOrder');

			$oTable->aColumn=array(
			'id'=>array('sTitle'=>'id_cart #',),
			'cat_name'=>array('sTitle'=>'Brand'),
// 			'code'=>array('sTitle'=>'CartCode'),
			'name'=>array('sTitle'=>'Name','sWidth'=>'30%'),
// 			'order_status'=>array('sTitle'=>'man_Order Status'),
			'price'=>array('sTitle'=>'Price'),
			'number'=>array('sTitle'=>'number'),
			'action'=>array(),
			);
			$oTable->iRowPerPage=200;
			$oTable->sDataTemplate='manager/row_order.tpl';
			//$oTable->sButtonTemplate='manager/button_order.tpl';
			//$oTable->bCheckVisible=true;
			//$oTable->sSubtotalTemplate='manager/subtotal_order.tpl';
			$oTable->bStepperVisible=false;

			Base::$sText.=$oTable->getTable();
			Base::$sText.=Base::$tpl->fetch('manager/button_add_order_item.tpl').$sPrintPac;

			return;
		}

		Base::Message();

		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		
		Base::$tpl->assign('aNameManager',$aNameManager=array(0 =>'')+Db::GetAssoc("select u.id, concat(um.name,' ( ',u.login,' )') as name
		from user as u
		inner join user_manager as um on u.id=um.id_user
		where u.visible=1
		order by um.name"));
		
		Base::$tpl->assign("aPref",$aPref=array(""=>"")+Db::GetAssoc("Assoc/Pref"));
		
		Base::$sText.=Base::$tpl->fetch('manager/link_package_search.tpl');
// 		if (Auth::$aUser['type_'] == 'manager'){
// 		    Resource::Get()->Add('/js/jquery.searchabledropdown-1.0.8.min.js',1);
// 		    Resource::Get()->Add('/js/select2.min.js',1);
// 		    Resource::Get()->Add('/css/select2.min.css');
// 		}

		//$this->AssignCustomers();
		$aOrderStatus=array(
		    ''=>Language::GetMessage('All'),
		    'work'=>Language::GetMessage('work'),
		    'pending'=>Language::GetMessage('pending'),
		    'end'=>Language::GetMessage('end'),
		    'refused'=>Language::GetMessage('refused'),
		);
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['id']=array('title'=>'CartPackage #','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['search_name']=array('title'=>'Manager','type'=>'select','options'=>$aNameManager,'name'=>'search_name','selected'=>Base::$aRequest['search_name'],'class'=>'select_name_manager');
		$aField['search_code']=array('title'=>'CartCode','type'=>'input','value'=>Base::$aRequest['search_code'],'name'=>'search_code');
		$aField['pref']=array('title'=>'Brand','type'=>'select','options'=>$aPref,'name'=>'search[pref]','selected'=>Base::$aRequest['search']['pref'],'class'=>'select_name_user');
		$aField['search_order_status']=array('title'=>'Status','type'=>'select','options'=>$aOrderStatus,'name'=>'search_order_status','selected'=>Base::$aRequest['search_order_status']);
		$aField['search_date']=array('title'=>'Only is_viewed','type'=>'checkbox','name'=>'search[is_viewed]','value'=>'1','checked'=>Base::$aRequest['search']['is_viewed']);
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aField['upload']=array('type'=>'button','class'=>'at-btn','value'=>'Upload','onclick'=>"change_form_action('id_order_report_form','manager_order_export_all');");
		
		$aData=array(
		'sHeader'=>"method=get id='id_order_report_form'",
		//'sTitle'=>"Order Items",
		//'sContent'=>Base::$tpl->fetch('manager/form_package_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_package_list',
		'sReturnButton'=>'Clear',
		//'sAdditionalButtonTemplate'=>'manager/button_order_all_export.tpl',
	    'bIsPost'=>0,
		'sWidth'=>'100%',
		'sError'=>$sError,
		    
		);
		
		$oForm=new Form($aData);

		$oForm->sReturn=Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']);
		//$oForm->sAdditionalButtonTemplate=$this->sPrefix.'/button_form_package_search.tpl';

		Base::$sText.=$oForm->getForm();

		// --- search ---
		if (Base::$aRequest['search']['is_viewed']) $sWhere.=" and cp.is_viewed ='0'";
		if (Base::$aRequest['search']['id']) $sWhere.=" and cp.id  in (".Base::$aRequest['search']['id'].")";
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (cp.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and cp.post_date  <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
		if (Base::$aRequest['search_login']) { 
			$sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search_name']) {
		    $sWhere.=" and uc.id_manager = '".Base::$aRequest['search_name']."' ";
		}
		if (Base::$aRequest['search_zip']) $sWhere.=" and uc.zip ='".Base::$aRequest['search_zip']."'";
		if (Base::$aRequest['search_order_status']) $sWhere.=" and cp.order_status ='".Base::$aRequest['search_order_status']."'";
		if (Base::$aRequest['search_code'] || Base::$aRequest['search']['pref']) {
			Base::$aRequest['search_code']=str_replace('-','',trim(Base::$aRequest['search_code']));
			$sJoin.=" inner join cart cart on cp.id=cart.id_cart_package ";
			if (Base::$aRequest['search_code']) $sWhere.=" and  cart.code='".Base::$aRequest['search_code']."'";
			if (Base::$aRequest['search']['pref']) $sWhere.=" and  cart.pref='".Base::$aRequest['search']['pref']."'";
		}
		// --------------

		$oTable=new Table();
		
		$oTable->sSql=Base::GetSql('CartPackage',array(
		'where'=>" and cp.is_archive='0' ".$sWhere.$sWhereManager,
		'join'=>$sJoin,
		));

		$oTable->aOrdered="order by cp.post_date desc";
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'cartpackage #'),
		'post'=>array('sTitle'=>'Date/Customer',"sWidth"=>"10%"),
		'part'=>array('sTitle'=>'Part / Brand [qty] Name',"sWidth"=>"40%"),
		'order_status'=>array('sTitle'=>'Order Status/<br>Address of delivery', "sWidth"=>"10%"),
		'price'=>array('sTitle'=>'Price'),
		'price_total'=>array('sTitle'=>'Total'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='manager/row_package.tpl';
		$oTable->sButtonTemplate='manager/button_package.tpl';
		$oTable->bCheckVisible=true;
		$oTable->bDefaultChecked=false;
		$oTable->iRowPerPage=50;
		$oTable->aCallback=array($this,'CallParsePackage');

		Base::$sText.= $oTable->getTable("Cart Packages",'cart_package_table');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParsePackage(&$aItem) {
		if ($aItem) {
			$oCurrency = new Currency();
			foreach($aItem as $sKey => $aValue) {
				$aCart=Db::GetAll(Base::GetSql("Part/Search",array("id_cart_package"=>$aValue["id"])));

				if ($aCart) foreach ($aCart as $sKeyItem=> $aCartItem) {
					if ($aCartItem['order_status']=='reclamation') $aItem[$sKey]['is_reclamation']=1;
					$aHistory=Base::$db->getAll("select * from cart_log
						where id_cart = ".$aCartItem["id"]);
					if ($aHistory) foreach($aHistory as $key => $value) {
						if ($value['is_customer_visible']==0 && !Auth::$aUser['is_super_manager'])
							continue;
						$aCart[$sKeyItem]['history'][$value['id']]=$value;
					}
					//$aCart[$sKeyItem]['history']=Db::GetAssoc("select cl.* from cart_log as cl	where id_cart = ".$aCartItem["id"]);
				}
				$aItem[$sKey]['aCart']=$aCart;

				$aFile=Db::GetAll(Base::GetSql("FileAttachment",array(
				"table_name"=>"cart_package",
				"table_id"=>$aValue['id'],
				)));
				if ($aFile) {
					$aItem[$sKey]['file']=$aFile;
				}
				$aItem[$sKey]['sAutoInfo'] = OwnAuto::GetAutoInfoTip($aValue['id']);
			}
			Base::$tpl->assign('sHeader',Language::GetMessage("Cart Packages"));
		}
		Base::$tpl->assign('sClass',"at-tab-table");
	}
	//-----------------------------------------------------------------------------------------------
	public function DeletePackageEmpty() {
		if (Base::$aRequest['id'])
		{
			$aCartPackage=Db::GetRow("select * from cart_package where id=".Base::$aRequest['id']);
			//if (Db::GetAll("select * from cart where id_cart_package=".Base::$aRequest['id'])) {

			$sMessage = Manager::CheckDeniedDeletePackage(Base::$aRequest['id']);
			if ($sMessage) {
				Form::RedirectAuto('&aMessage[MT_ERROR_NT]='.$sMessage);
				return;
			}
				
			if ($aCartPackage['order_status']=="pending"
			|| ($aCartPackage['order_status']=="work" && $aCartPackage['is_payed']==0))
			{
				/*Db::Execute("update user_account as ua
				inner join user_account_log as ual on ua.id_user=ual.id_user and section='cart_package'
				and custom_id=".Base::$aRequest['id']." set ua.amount=ua.amount-ual.amount");*/

				Db::Execute("delete from cart_package where id=".Base::$aRequest['id']);
				Db::Execute("delete from cart where id_cart_package=".Base::$aRequest['id']);
				Db::Execute("delete from cart_package_log where id_cart_package=".Base::$aRequest['id']);

				/*Db::Execute("delete from user_account_log
					where custom_id=".Base::$aRequest['id'] );*/

				if ($aCartPackage['order_status']=="pending")
				{ } // не было движений
				else {
				    // заказ брали в работу или сразу удаление детали?
				    $iExistWorkPackage = Db::getOne("Select id from user_account_log where custom_id=".$aCartPackage['id']." and operation='pending_work'");
				    if ($iExistWorkPackage) {
    					// return money
    					Finance::Deposit($aCartPackage['id_user'],$aCartPackage['price_total'],
    						Language::getMessage('return money for order'),
    						$aCartPackage['id'],'internal','package_return','',5);
				    }
				}								
				$sMessage="&aMessage[MT_NOTICE]=Package deleted";
			} else {
				$sMessage="&aMessage[MT_ERROR]=Package is not new or payed";
			}

		} else $sMessage="&aMessage[MT_ERROR]=Check Package";

		Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	//	public function PackageArchive($sType='cart') {
	//		if (Base::$aRequest['row_check']) {
	//			$sQuery="update cart_package set
	//					is_archive='1'
	//				where id in (".implode(',',Base::$aRequest['row_check']).")
	//					".Auth::$sWhere;
	//			Base::$db->Execute($sQuery);
	//		}
	//		$this->PackageList();
	//	}
	//-----------------------------------------------------------------------------------------------
	public function ExportAll()
	{
		$this->sExportSql=$_SESSION['order']['current_sql'];
		$this->Export('sql');
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderReportExport()
	{
	    $aField['cat_name']=array('title'=>'Brand','type'=>'input','value'=>Base::$aRequest['search']['cat_name'],'name'=>'search[cat_name]');
	    $aField['code']=array('title'=>'Code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
	    $aField['name_translate']=array('title'=>'Name','type'=>'input','value'=>Base::$aRequest['search']['name_translate'],'name'=>'search[name_translate]');
	    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
	    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
	    
	    $aField['upload']=array('type'=>'button','class'=>'at-btn','value'=>Upload,'onclick'=>"change_form_action('id_order_report_form','manager_order_export');  this.form.submit();");
	    
	    $aData=array(
	        'sHeader'=>"method='get' id='id_order_report_form' ",
	        'sTitle'=>"Export_Cart_Order_Report",
	        //'sContent'=>Base::$tpl->fetch('manager/form_export_report.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search', 
	        'sSubmitAction'=>'manager_order_report',
	        'sReturnButton'=>'Clear',
	        //'sAdditionalButtonTemplate'=>'manager/button_order_export.tpl',
	        'bIsPost'=>0,
	        'sWidth'=>'30%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    
	    Base::$sText.=$oForm->getForm();
	    
	    // --- search ---
	    if (Base::$aRequest['search']['cat_name']) 
	        $sWhere.="  and cat_name like '%".Base::$aRequest['search']['cat_name']."%' ";
	    if (Base::$aRequest['search']['code'])
	        $sWhere.=" and code ='".Base::$aRequest['search']['code']."'";
	    if (Base::$aRequest['search']['name_translate']) {
	        $sWhere.=" and name_translate like '%".Base::$aRequest['search']['name_translate']."%'";
	    }
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."' 
	            and post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
	    // --- search ---
	    $oTable=new Table();
	    $oTable->sSql="select 
	        c.*, count(*) as number 
	        from (select * from cart where type_='order' and order_status='end' ".$sWhere." ) as c
	        where 1=1
	        group by item_code";
	    
	    $oTable->aColumn=array(
	        'cat_name'=>array('sTitle'=>'Brand'),
	        'code'=>array('sTitle'=>'Code'),
	        'name_translate'=>array('sTitle'=>'Name','sWidth'=>'60%'),
	        'number'=>array('sTitle'=>'number'),
	    );
	    $oTable->aOrdered="order by number desc";
	    $oTable->iRowPerPage=30;
	    $oTable->sDataTemplate='manager/row_order_report.tpl';
	    $oTable->aCallback=array($this,'CallParseOrder');
	  
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function ExportOrder()
	{
	    // --- search ---
	    if (Base::$aRequest['search']['cat_name']) 
	        $sWhere.="  and cat_name like '%".Base::$aRequest['search']['cat_name']."%' ";
	    if (Base::$aRequest['search']['code'])
	        $sWhere.=" and code ='".Base::$aRequest['search']['code']."'";
	    if (Base::$aRequest['search']['name_translate']) {
	        $sWhere.=" and name_translate like '%".Base::$aRequest['search']['name_translate']."%'";
	    }
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."' 
	            and post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
	    // --- search ---
	    
	        $sSql="select 
	        c.*, count(*) as number 
	        from (select * from cart where type_='order' and order_status='end' ".$sWhere." ) as c
	        where 1=1
	        group by item_code order by number desc";

	        $aCart=Base::$db->getAll($sSql);
	    if ($aCart) {
	        $oExcel = new Excel();
	        $aHeader=array(
	            'A'=>array("value"=>'cat_name'),
	            'B'=>array("value"=>'code', "autosize"=>true),
	            'C'=>array("value"=>'name_translate', "autosize"=>true),
	            'D'=>array("value"=>'number', "autosize"=>true),
	        );
	        $oExcel->SetHeaderValue($aHeader,1,false);
	        $oExcel->SetAutoSize($aHeader);
	        $oExcel->DuplicateStyleArray("A1:U1");
	
	        $i=$j=2;
	        foreach ($aCart as $aValue)
	        {
	            $sMake=substr($aValue['item_code'],0,2);
	            if (strlen($aValue['cat_name'])==2) $sMake=$aValue['cat_name'];
	            $sMake=str_ireplace('LX','LS',$sMake);
	            $sMake=str_ireplace('HY','HU',$sMake);
	
	            $oExcel->setCellValue('A'.$i, $aValue['cat_name']);
	            $oExcel->setCellValue('B'.$i, $aValue['code']);
	            $oExcel->setCellValue('C'.$i, $aValue['name_translate']);
	            $oExcel->setCellValue('D'.$i, $aValue['number']);
	
	            $i++;
	        }
	        //end 
	        $sFileName=uniqid().'.xls';
	        $oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
	    }
	    else $sFileName='EmptyData.xls';
	    Base::$tpl->assign('sFileName',$sFileName);
	    Base::$sText.=Base::$tpl->fetch('manager/export.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function ExportOrderAll()
	{
	  // --- search ---
		if (Base::$aRequest['search']['is_viewed']) $sWhere.=" and cp.is_viewed ='0'";
		if (Base::$aRequest['search']['id']) $sWhere.=" and cp.id  in (".implode(",", Base::$aRequest['search']['id']).")";
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (cp.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and cp.post_date  <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
		if (Base::$aRequest['search_login']) { 
			$sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
			$sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search_name']) {
		    $sWhere.=" and uc.id_manager = '".Base::$aRequest['search_name']."' ";
		}
		if (Base::$aRequest['search_zip']) $sWhere.=" and uc.zip ='".Base::$aRequest['search_zip']."'";
		if (Base::$aRequest['search_order_status']) $sWhere.=" and cp.order_status ='".Base::$aRequest['search_order_status']."'";
		if (Base::$aRequest['search_code'] || Base::$aRequest['search']['pref']) {
			Base::$aRequest['search_code']=str_replace('-','',trim(Base::$aRequest['search_code']));
			$sJoin.=" inner join cart cart on cp.id=cart.id_cart_package ";
			if (Base::$aRequest['search_code']) $sWhere.=" and  cart.code='".Base::$aRequest['search_code']."'";
			if (Base::$aRequest['search']['pref']) $sWhere.=" and  cart.pref='".Base::$aRequest['search']['pref']."'";
		}
		// --------------
        $sSql=Base::GetSql('CartPackage',array(
 		    'where'=>" and cp.is_archive='0' and cp.id_user in (".$this->sCustomerSql.") ".$sWhere,
            'join'=>$sJoin,
 		));
	
	    $aCart=Base::$db->GetAll($sSql);
	    $this->CallParsePackage($aCart);
	    
	    //$this->CallParseOrder($aCart);
	   
	   
	    if ($aCart) {
	        $oExcel = new Excel();
	        
	        $aHeader=array(
	            'A'=>array("value"=>'cartpackage #', "autosize"=>true),
	            'B'=>array("value"=>'post_date', "autosize"=>true),
	            'C'=>array("value"=>'LOGIN', "autosize"=>true),
	            'D'=>array("value"=>'user_name', "autosize"=>true),
	            'E'=>array("value"=>'manager_login', "autosize"=>true),
	            'F'=>array("value"=>'code', "autosize"=>true),
	            'G'=>array("value"=>'ORDER_STATUS', "autosize"=>true),
	            'H'=>array("value"=>'cat_name', "autosize"=>true),
	            'I'=>array("value"=>'number', "autosize"=>true),
	            'J'=>array("value"=>'name_translate', "autosize"=>true),
	            'K'=>array("value"=>'Price', "autosize"=>true),
	            'L'=>array("value"=>'Total', "autosize"=>true),
	            'M'=>array("value"=>'price_original', "autosize"=>true),
	            'N'=>array("value"=>'total_original', "autosize"=>true),
	            'O'=>array("value"=>'profit', "autosize"=>true),
	            'P'=>array("value"=>'provider', "autosize"=>true),
	        );
	        $oExcel->SetHeaderValue($aHeader,1,false);
	        $oExcel->SetAutoSize($aHeader);
	        $oExcel->DuplicateStyleArray("A1:P1");
	
	        $i=$j=2;
	        if($aCart) foreach ($aCart as $aValuePackage)
	        {
	            if($aValuePackage['aCart']) foreach ($aValuePackage['aCart'] as $aValue)
	            {	            
	            $oExcel->setCellValue('A'.$i, $aValuePackage['id']);
	            $oExcel->setCellValue('B'.$i, $aValuePackage['post_date']);
	            $oExcel->setCellValue('C'.$i, $aValuePackage['login']);
	            $oExcel->setCellValue('D'.$i, $aValuePackage['name']);
	            $oExcel->setCellValue('E'.$i, $aValuePackage['manager_login']);
	            $oExcel->setCellValue('F'.$i, $aValue['code']);
	            $oExcel->setCellValue('G'.$i, $aValue['order_status']);
	            $oExcel->setCellValue('H'.$i, $aValue['cat_name']);
	            $oExcel->setCellValue('I'.$i, $aValue['number']);
	            $oExcel->setCellValue('J'.$i, $aValue['name_translate']);
	            $oExcel->setCellValue('K'.$i, $aValue['price']);
	            $oExcel->setCellValue('L'.$i, $aValue['price_total']);
	            $oExcel->setCellValue('M'.$i, Currency::PrintSymbol($aValue['price_original'],$aValue['id_currency_provider']));
	            $oExcel->setCellValue('N'.$i, Currency::PrintSymbol($aValue['total_original'],$aValue['id_currency_provider']));
	            $oExcel->setCellValue('O'.$i, $aValue['total_profit']);
	            $oExcel->setCellValue('P'.$i, $aValue['provider_name']);
	
	            $i++;
	           }
	        }

	        //end
	        $sFileName=uniqid().'.xls';
	        $oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
	    }
	    else $sFileName='EmptyData.xls';
	    Base::$tpl->assign('sFileName',$sFileName);
	    Base::$sText.=Base::$tpl->fetch('manager/export.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function Export($sType='row_check')
	{
		if ($sType=='row_check' &&Base::$aRequest['row_check']) {
			$sSql=Base::GetSql("Part/Search",array(
			"where"=>"and c.id_user in (".$this->sCustomerSql.")
					and c.id in (".implode(',',Base::$aRequest['row_check']).")"));

		if ($sType=='sql') {
			$sSql=$this->sExportSql;
		}

		$aCart=Base::$db->getAll($sSql);
		if ($aCart) {
			$oExcel = new Excel();
			$aHeader=array(
			'A'=>array("value"=>'date'),
			'B'=>array("value"=>'id_detal', "autosize"=>true),
			'C'=>array("value"=>'id_package', "autosize"=>true),
			'D'=>array("value"=>'Make', "autosize"=>true),
			'E'=>array("value"=>'Code', "autosize"=>true),
			'F'=>array("value"=>'Customer', "autosize"=>true),
			'G'=>array("value"=>'Customer_name', "autosize"=>true),
			'H'=>array("value"=>'Name_Russian'),
			'I'=>array("value"=>'Name'),
			'J'=>array("value"=>'Order_status'),
			'K'=>array("value"=>'Number'),
			'L'=>array("value"=>'Price'),
			'M'=>array("value"=>'Total'),
			'N'=>array("value"=>'Provider'),
			'O'=>array("value"=>'Sign'),
			'P'=>array("value"=>'OriginalPrice'),
			'Q'=>array("value"=>'OriginalTotal'),
			'R'=>array("value"=>'address'),
			);
			$aHeader['S']=array("value"=>Language::getMessage('XLS_Diff'));
			$aHeader['T']=array("value"=>Language::getMessage('XLS_Invoice'));
			$aHeader['U']=array("value"=>Language::getMessage('XLS_Order'));
			$aHeader['V']=array("value"=>Language::getMessage('XLS_Provider_price'));

			$aHeader['W']=array("value"=>'manager');
			
			$oExcel->SetHeaderValue($aHeader,1,false);
			$oExcel->SetAutoSize($aHeader);
			$oExcel->DuplicateStyleArray("A1:W1");

			$i=$j=2;
			foreach ($aCart as $aValue)
			{
				$oExcel->setCellValue('A'.$i, $aValue['post_date']);
				$oExcel->setCellValue('B'.$i, $aValue['id']);
				$oExcel->setCellValue('C'.$i, $aValue['id_cart_package']);
				$oExcel->setCellValue('D'.$i, $aValue['cat_name']);
				$oExcel->setCellValue('E'.$i, " ".$aValue['code']);
				$oExcel->setCellValue('F'.$i, $aValue['login']);
				$oExcel->setCellValue('G'.$i, strip_tags($aValue['customer_name']));
				$oExcel->setCellValue('H'.$i, strip_tags($aValue['name_translate']));
				$oExcel->setCellValue('I'.$i, strip_tags($aValue['name']));
				$oExcel->setCellValue('J'.$i, $aValue['order_status'] .' '.$aValue['sign'] );
				$oExcel->setCellValue('K'.$i, $aValue['number']);
				$oExcel->setCellValue('L'.$i, $aValue['price']);
				$oExcel->setCellValue('M'.$i, round($aValue['number']*$aValue['price'],2));

				if (Auth::$aUser['is_super_manager'] || Auth::$aUser['is_sub_manager'])
				$oExcel->setCellValue('N'.$i, $aValue['provider_name']);
				else $oExcel->setCellValue('N'.$i, '');

				$dPriceOriginal=($aValue['provider_price']>0 ? $aValue['provider_price'] : $aValue['price_original']);

				$oExcel->setCellValue('O'.$i, $aValue['sign']);
				$oExcel->setCellValue('P'.$i, Currency::PrintSymbol($aValue['price_original'],$aValue['id_currency_provider']));
				$oExcel->setCellValue('Q'.$i, Currency::PrintSymbol(round($aValue['number']*$aValue['price_original'],2),$aValue['id_currency_provider']));
				$oExcel->setCellValue('R'.$i, $aValue['country'].";".$aValue['city']."; ".$aValue['address']);

				$oExcel->setCellValue('S'.$i, $aValue['number']*($aValue['price']-$dPriceOriginal));
				$oExcel->setCellValue('T'.$i, $aValue['id_provider_invoice']);
				$oExcel->setCellValue('U'.$i, $aValue['id_provider_order']);
				$oExcel->setCellValue('V'.$i, Currency::PrintSymbol($aValue['provider_price'],$aValue['id_currency_provider']));
				
				$oExcel->setCellValue('W'.$i, $aValue['manager_name']);

				$i++;
			}

			//new sheet
			$oExcel->CreateSheet();
			$oExcel->SetActiveSheetIndex(1);
			$oExcel->SetTitle(Language::GetMessage("Order to the provider"));
			$aHeader=array(
			'A'=>array("value"=>'Marka', "autosize"=>true),
			'B'=>array("value"=>'Code', "autosize"=>true),
			'C'=>array("value"=>'Name', "autosize"=>true),
			'D'=>array("value"=>'Number', "autosize"=>true),
			'E'=>array("value"=>'Price', "autosize"=>true),
			'F'=>array("value"=>'Total', "autosize"=>true),
			);
			$oExcel->SetHeaderValue($aHeader,1,false);
			$oExcel->SetAutoSize($aHeader);
			$oExcel->DuplicateStyleArray("A1:F1");
			$i=$j=2;
			foreach ($aCart as $aValue)
			{
				/*$sMake=substr($aValue['item_code'],0,2);
				if (strlen($aValue['cat_name'])==2) $sMake=$aValue['cat_name'];
				$sMake=str_ireplace('LX','LS',$sMake);
				$sMake=str_ireplace('HY','HU',$sMake);*/

				$oExcel->setCellValue('A'.$i, $aValue['cat_name']);
				//$oExcel->setCellValue('A'.$i, $sMake);
				$oExcel->setCellValue('B'.$i, " ".$aValue['code']);
				if ($aValue['name']<>" ") $oExcel->setCellValue('C'.$i, strip_tags($aValue['name']));
				else $oExcel->setCellValue('C'.$i, strip_tags($aValue['name_translate']));
				$oExcel->setCellValue('D'.$i, $aValue['number']);
				$oExcel->setCellValue('E'.$i, Currency::PrintSymbol($aValue['price_original'],$aValue['id_currency_provider']));
				$oExcel->setCellValue('F'.$i, Currency::PrintSymbol(round($aValue['number']*$aValue['price_original'],2),$aValue['id_currency_provider']));

				$i++;
			}
			//end new sheet
			$sFileName=uniqid().'.xls';
			$oExcel->WriterExcel5(SERVER_PATH.'/imgbank/temp_upload/'.$sFileName, true);
		}
		else $sFileName='EmptyData.xls';

		Base::$tpl->assign('sFileName',$sFileName);
		Base::$sText.=Base::$tpl->fetch('manager/export.tpl');
	   }
	   else 
	       Base::Redirect('/pages/manager_order');
	}
	//-----------------------------------------------------------------------------------------------
		public function ExportMegaAll() {
			$this->sExportMegaSql=$_SESSION['order']['current_sql'];
			$this->ExportMega('sql');
		}
	//-----------------------------------------------------------------------------------------------
		public function ExportMega($sType='row_check') {
			//error_reporting(E_ALL ^ E_NOTICE);
			if ($sType=='row_check' &&Base::$aRequest['row_check']) {
				$sSql="select u.*, uc.name as customer_name, c.*
					 from cart c
					 inner join user u on c.id_user=u.id
					 inner join user_customer uc on uc.id_user=u.id
					where 1=1 and c.type_='order'
						and c.id_user in (".$this->sCustomerSql.")
						and c.id in (".implode(',',Base::$aRequest['row_check']).")
						";
			} elseif ($sType=='sql') {
				$sSql=$this->sExportMegaSql;
			}
	
			//Base::$sText.=$sSql."<br>";
			$aCart=Base::$db->getAll($sSql);
			if ($aCart) {
				require_once(SERVER_PATH.'/class/module/Catalog.php');
				foreach ($aCart as $aValue) {
					$aPartItem=array();
					$aPartItem['Confirm']='1';
					$aPartItem['MakeLogo']=$this->GetCartMake($aValue);
					$aPartItem['DetailNum']=Catalog::StripCode($aValue['code']);
					$aPartItem['Destinationlogo']='EMEW';
					$aPartItem['PriceId']='103';
					$aPartItem['Quantity']=$aValue['number'];
					if (stripos($aValue['sign'],'ONLY')!==false) $aPartItem['bitOnly']=1;
					else $aPartItem['bitOnly']=0;
					if (stripos($aValue['sign'],'QUAN')!==false) $aPartItem['bitQuantity']=1;
					else $aPartItem['bitQuantity']=0;
					if (stripos($aValue['sign'],'WAIT')!==false) $aPartItem['bitWait']=1;
					else $aPartItem['bitWait']=0;
					if (stripos($aValue['sign'],'AGRE')!==false) $aPartItem['bitAgree']=1;
					else $aPartItem['bitAgree']=0;
					if (stripos($aValue['sign'],'BRAND')!==false) $aPartItem['OnlyThisBrand']=1;
					else $aPartItem['OnlyThisBrand']=0;
					$aPartItem['Reference']=$aValue['login'];
					$aPartItem['CustomerSubId']=$aValue['id'];
					$aPart[]=$aPartItem;
				}
				//Base::$sText.=print_r($aPart,true)."<br>";
				require_once(SERVER_PATH.'/class/module/ManagerService.php');
				$oManagerService=new ManagerService();
				$oManagerService->SendToCartAll($aPart);
	
				Base::$sText.=Language::getText('Items are sent to Mega Cart. Please login there and send the order.');
			}
			else {
				Base::$sText.=Language::getText('No items selected.');
			}
			Base::$sText.=Base::$tpl->fetch('manager/button_mega_return.tpl');
		}
	//-----------------------------------------------------------------------------------------------
	public function GetCartMake($aCart)
	{
		//		if ($aCart['login_vin_request']) {
		//			return $aCart['cat_name'];
		//		}
		//		else {
		return substr($aCart['item_code'],0,strpos($aCart['item_code'],'_'));
		//		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportStatus()
	{
		if (Base::$aRequest['is_post']) {
			if (is_uploaded_file($_FILES['import_file']['tmp_name'])) {

				require_once(SERVER_PATH.'/lib/excel/reader.php');
				$oReader = new Spreadsheet_Excel_Reader();
				$oReader->setOutputEncoding('CP1251');
				$oReader->read($_FILES['import_file']['tmp_name']);

				$aResult=$oReader->sheets[0]['cells'];
				if ($aResult) foreach ($aResult as $key=>$value) {

					$value['id']=trim($value[1]);
					$iSpace=stripos($value['id'],' ');
					if ($iSpace) $value['id']=substr($value['id'],0,$iSpace);
					$value['id']=preg_replace('/[^0-9]/', '',$value[id]);

					$value['order_status']=$value[2];
					$value['id_provider_order']=$value[4];
					$value['provider_price']=$value[5];
					$value['id_provider_invoice']=$value[6];
					$value['custom_value']=$value[7];
					if ($value[id] && $value[order_status]) {
						$value['comment']=$value[3];
						$value['message']=$this->ProcessOrderStatus($value['id'],$value['order_status'],$value['comment']
						,$value['id_provider_order'],$value['provider_price'],$value['id_provider_invoice'],$value['custom_value']);
						$value['old_order_status']=$this->sCurrentOrderStatus;
						$aProcessed[]=$value;
					}
				}
				Base::$tpl->assign('aProcessed',$aProcessed);
			}
			else Base::Redirect('/?action=manager_import_status');
		}

		$aField['import_file']=array('title'=>'File to import','type'=>'file','name'=>'import_file');
		$aField['ignore_confirm_growth']=array('title'=>'Ignore confirm price growth','type'=>'checkbox','name'=>'ignore_confirm_growth','value'=>1);
		$aField['is_post']=array('type'=>'hidden','name'=>'is_post','value'=>'1');
		
		$aData=array(
		'sHeader'=>"method=post enctype='multipart/form-data'",
		'sTitle'=>"Import Cart Statuses",
		//'sContent'=>Base::$tpl->fetch('manager/form_import_status.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Upload',
		'sSubmitAction'=>'manager_import_status',
		'sReturnButton'=>'Return',
		'sReturnAction'=>'manager_order',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();

		if ($aProcessed) Base::$sText.=Base::$tpl->fetch('manager/processed_import.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function EditWeight () {
		Auth::NeedAuth('manager');
		Base::$tpl->assign("aPref",array(""=>"")+Db::GetAssoc("Assoc/Pref"));

		/* [ apply  */
		if (Base::$aRequest['is_post'])
		{
			$aData=String::FilterRequestData(Base::$aRequest['data']);
			if (Base::$aRequest['item_code']) {
				list($aData['pref'],$aData['code'])=explode('_',$aData['item_code']);
				$aData['comment']='edit manager';

				if (isset($aData['item_code']) && trim($aData['item_code'])!="") {
					Manager::AddWeightName($aData);
				}
				$sMessage="&aMessage[MT_NOTICE]=changed";
			}
			Form::RedirectAuto($sMessage);

		}
		/* ] apply */

		if (Base::$aRequest['action']==$this->sPrefix.'_add_weight' || Base::$aRequest['action']==$this->sPrefix.'_edit_weight')
		{
			if (Base::$aRequest['action']==$this->sPrefix.'_edit_weight')
			{
				Base::$tpl->assign('aData',$aData=Base::$db->getRow(Base::GetSql("CatPart",array(
				"item_code"=>Base::$aRequest['item_code']
				))));
			}

			Resource::Get()->Add('/js/form.js',3284);
			
			$aField['code']=array('title'=>'Item code','type'=>'text','value'=>Base::$aRequest['item_code']);
			$aField['item_code']=array('type'=>'hidden','name'=>'data[item_code]','value'=>Base::$aRequest['item_code']);
			$aField['name_rus']=array('title'=>'Name ru','type'=>'input','value'=>Base::$aRequest['name'],'name'=>'data[name_rus]');
			$aField['weight']=array('title'=>'Weight','type'=>'input','value'=>$aData['weight'],'name'=>'data[weight]','id'=>'weight');
			$aField['unit_name']=array('title'=>'Unit name','type'=>'input','value'=>$aData['unit_name']?$aData['unit_name']:'шт.');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Edit weight, name",
			//'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_weight.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>$this->sPrefix."_edit_weight",
			'sReturnButton'=>'<< Return',
			'bAutoReturn'=>true,
			'sWidth'=>"500px",
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();

			return;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ImportWeight()
	{
		if (Auth::$aUser['id_manager_partner'] || Auth::$aUser['id_customer_partner']) Base::Redirect('/?action=auth_type_error');

		//Base::$aTopPageTemplate=array('panel/tab_manager_price.tpl'=>'import_weight');

		if (Base::$aRequest['is_post'] && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
			set_time_limit(0);

			$oExcel= new Excel();
			$ext = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
			if ($ext == 'xls')
				$oExcel->ReadExcel5($_FILES['import_file']['tmp_name'],true);
			else
				$oExcel->ReadExcel7($_FILES['import_file']['tmp_name'],true);
			
			$oExcel->SetActiveSheetIndex();
			$oExcel->GetActiveSheet();

			$aResult=$oExcel->GetSpreadsheetData();

			if ($aResult) {
				$aPref=Base::$db->getAssoc("select cp.name,c.pref from cat_pref cp inner join cat c on c.id=cp.cat_id");
				$aPrefName=Base::$db->getAssoc("select id,name from cat_pref");

				foreach ($aResult as $key=>$value) {
					unset($u);

					$u['pref']=strtoupper($value[1]);
					if (in_array($u['pref'],$aPrefName)) {
						$u['pref']=$aPref[$u['pref']];
						$u['code']=Catalog::StripCode($value[2]);
						$u['item_code']=$u['pref']."_".$u['code'];
						$u['name_rus']=trim($value[3]);
						$u['weight']=str_replace(",",".",$value[4]);
						$u['comment']='excel_import';
					}

					if (isset($u['item_code']) && trim($u['item_code'])!="") {
						Manager::AddWeightName($u);
						$aProcessed[]=$u;
					}

				}
			}

			if (count($aProcessed)<10000) Base::$tpl->assign('aProcessed',$aProcessed);
			else Base::$tpl->assign('aProcessed',array(0=>Language::GetMessage("Data load OK")));

		}

		$aField['code']=array('title'=>'code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
		$aField['comment']=array('title'=>'comment','type'=>'input','value'=>Base::$aRequest['search']['comment'],'name'=>'search[comment]');
		$aField['import_file']=array('title'=>'File to import','type'=>'file','name'=>'import_file');
		$aField['is_post']=array('type'=>'hidden','name'=>'is_post','value'=>'1');
		
		$aData=array(
		'sHeader'=>"method=post enctype='multipart/form-data'",
		'sTitle'=>"Import Weight and Name",
		//'sContent'=>Base::$tpl->fetch('manager/form_import_weight.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Upload/Search',
		'sSubmitAction'=>'manager_import_weight',
		'sReturnButton'=>'Return',
		'sReturnAction'=>'manager_import_weight',
		'bIsPost'=>0,
		'sWidth'=>'650px',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();

		if ($aProcessed) Base::$sText.=Base::$tpl->fetch('manager/processed_weight.tpl');
		else {
			// --- search ---
			if (Base::$aRequest['search']['comment']) $sWhere.=" and cpw.comment like '%".Base::$aRequest['search']['comment']."%'";
			// --------------

			$oTable=new Table();
			$oTable->iRowPerPage=50;
			$oTable->sSql=Base::GetSql("CatPart",array(
			'code'=>Base::$aRequest['search']['code'],
			'weight_log'=>1,
			'where'=>$sWhere,
			));

			$oTable->aOrdered="order by cpw.id desc";
			$oTable->aColumn=array(
			'item_code'=>array('sTitle'=>'Item_code'),
			'weight'=>array('sTitle'=>'Weight'),
			'name_rus'=>array('sTitle'=>'Name rus'),
			'login'=>array('sTitle'=>'Login'),
			'post_date'=>array('sTitle'=>'Date'),
			'comment'=>array('sTitle'=>'Comment'),
			);
			$oTable->sDataTemplate='manager/row_cat_part_weight.tpl';
			//$oTable->sButtonTemplate='manager/button_finance.tpl';
			//$oTable->sSubtotalTemplate='manager/subtotal_finance.tpl';
			//$oTable->aCallback=array($this,'CallParseLog');

			Base::$sText.=$oTable->getTable();
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AddWeightName($u)
	{
		Db::Execute("
		insert into cat_part (item_code, pref, code, name_rus, name_ua, weight, unit_name)
		values ('".$u['item_code']."','".$u['pref']."','".$u['code']
	    ."','".mysql_escape_string($u['name_rus'])."','"
	    .mysql_escape_string($u['name_ua'])."','"
	    .$u['weight']."','".$u['unit_name']."')
		on duplicate key update name_rus='".mysql_escape_string($u['name_rus'])."'
		, name_ua='".mysql_escape_string($u['name_ua'])."'
		, weight=if('".$u['weight']."'='', weight, '".$u['weight']."')
		, unit_name=if('".$u['unit_name']."'='', unit_name, '".$u['unit_name']."')"
						);
		/*Db::Execute("
		insert into cat_part (item_code, pref, code, name_rus, weight, unit_name)
		values ('".$u['item_code']."','".$u['pref']."','".$u['code']."','"
		.mysql_escape_string($u['name_rus'])."','".$u['weight']."','".$u['unit_name']."')
		on duplicate key update name_rus=if('".mysql_escape_string($u['name_rus'])."'='',name_rus, '"
		.mysql_escape_string($u['name_rus'])."')
		, weight=if('".$u['weight']."'='', weight, '".$u['weight']."')
		, unit_name=if('".$u['unit_name']."'='', unit_name, '".$u['unit_name']."')"
		);
		*/

		/* not used now ?
		 * $id_cat_part=Db::GetOne("select id from cat_part where item_code='".$u['item_code']."'");

		if ($id_cat_part){
			$aCartPartWeight=array(
			'id_user'=>Auth::$aUser['id'],
			'id_cat_part'=>$id_cat_part,
			'weight'=>$u['weight'],
			'name_rus'=>$u['name_rus'],
			'comment'=>$u['comment'],
			);
			Db::AutoExecute('cat_part_weight',$aCartPartWeight);
		}*/
	}
	//-----------------------------------------------------------------------------------------------
	public function AssignCustomers()
	{
		/* Commented unsed method */
		//		$sSql="select cg.*,u.*,uc.* from user u
		//					inner join user_customer uc on u.id=uc.id_user
		//					inner join customer_group cg on uc.id_customer_group=cg.id
		//					 where 1=1
		//					 	and u.id in (".$this->sCustomerSql.")";
		//		Base::$tpl->assign('aCustomer',Base::$db->getAll($sSql));
	}
	//-----------------------------------------------------------------------------------------------
	public function Finance()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'finance');

		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['data']['amount'] || !Base::$aRequest['data']['id_user']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_finance_add';
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {

				$sDescription = (Base::$aRequest['data']['description'] ? Base::$aRequest['data']['description']:
				Language::GetMessage('manager money deposit')) ;

				Finance::Deposit(Base::$aRequest['data']['id_user'],
				Base::$aRequest['data']['amount'],
				$sDescription,
				Base::$aRequest['data']['custom_id'],
				Base::$aRequest['data']['pay_type'],
				'internal',
				''
				,Base::$aRequest['data']['id_user_account_log_type']);

				//Buh::EntrySingle(array(),311,361,Base::$aRequest['data']['amount'],$sDescription
				//,);
				$this->PayCartPackage(Base::$aRequest['data']['custom_id']);

				Form::RedirectAuto("&aMessage[MI_NOTICE]=payment added");
			}
		}

		if (Base::$aRequest['action']=='manager_finance_add') {

			Base::$tpl->assign('aUserAccountLogType',$aUserAccountLogType=Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc',array(
			'where'=>" and ualt.id in (1,8)"
			))));
// 			Base::$tpl->assign('aPayTypeId', BaseTemp::EnumToArray("user_account_log","pay_type"));
// 			Base::$tpl->assign('aPayTypeValue', BaseTemp::EnumToArray("user_account_log","pay_type"));
			
			$aPayType=BaseTemp::EnumToArray("user_account_log","pay_type");
			foreach ($aPayType as $key=>$value){
			   $aPayType[$value]=$value; 
			   unset($aPayType[$key]);
			}
			Base::$tpl->assign('aPayType', $aPayType);
			
			$aField['login']=array('title'=>'Deposit to customer','type'=>'text','value'=>Base::$aRequest['login']);
			if(Base::$aRequest['custom_id']) $aField['custom_id']=array('title'=>'Deposit to cart package','type'=>'text','value'=>Base::$aRequest['custom_id']);
			$aField['hr']=array('type'=>'hr','colspan'=>2);
			$aField['amount']=array('title'=>'Amount','type'=>'input','value'=>$aData['amount'],'name'=>'data[amount]','szir'=>1);
			$aField['id_user_account_log_type']=array('title'=>'Account Log Type','type'=>'select','options'=>$aUserAccountLogType,'name'=>'data[id_user_account_log_type]','szir'=>1);
			$aField['pay_type']=array('title'=>'Pay Type','type'=>'select','options'=>$aPayType,'name'=>'data[pay_type]','szir'=>1);
			$aField['custom_id']=array('title'=>'Custom ID','type'=>'input','value'=>$aData['custom_id']?$aData['custom_id']:Base::$aRequest['custom_id'],'name'=>'data[custom_id]');
			$aField['description']=array('title'=>'Description','type'=>'textarea','name'=>'data[description]');
			$aField['id_user']=array('type'=>'hidden','name'=>'data[id_user]','value'=>Base::$aRequest['id_user']);
			$aField['return']=array('type'=>'hidden','name'=>'data[return]','value'=>urlencode(Base::$aRequest['return']));
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Finance add",
			//'sContent'=>Base::$tpl->fetch('manager/form_finance.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_finance_add',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();
			return;
		}

// 		$aUserAccountLogTypeAll=array(
// 		    ''=>Language::GetMessage('All'),
// 		);

		Base::$tpl->assign('aUserAccountLogType',$aUserAccountLogType=Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc')));
		$aUserAccountLogType=(array(''=>Language::GetMessage('All'))+$aUserAccountLogType);
		
		$aField['login']=array('title'=>'Customer','type'=>'input','value'=>Base::$aRequest['search']['login'],'name'=>'search[login]');
		$aField['custom_id']=array('title'=>'Ual CustomId','type'=>'input','value'=>Base::$aRequest['search']['custom_id'],'name'=>'search[custom_id]');
		$aField['id_user_account_log_type']=array('title'=>'Account Log Type','type'=>'select','options'=>$aUserAccountLogType,'selected'=>Base::$aRequest['search']['id_user_account_log_type'],'name'=>'search[id_user_account_log_type]');
		$aField['description']=array('title'=>'Description','type'=>'input','value'=>Base::$aRequest['search']['description'],'name'=>'search[description]');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager/form_finance_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_finance',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sWidth'=>'700px',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();



		// --- search ---
		if (Base::$aRequest['search']['login']) $sWhere.=" and u.login ='".Base::$aRequest['search']['login']."'";

		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and ual.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ual.post_date<'".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		}
		if (Base::$aRequest['search']['description'])
		$sWhere.=" and ual.description like '%".Base::$aRequest['search']['description']."%'";

		if (Base::$aRequest['search']['id_user_account_log_type']) {
			$sWhere.=" and ual.id_user_account_log_type='".Base::$aRequest['search']['id_user_account_log_type']."'";
		}
		if (Base::$aRequest['search']['custom_id']) {
			$sWhere.=" and ual.custom_id='".Base::$aRequest['search']['custom_id']."'";
		}
		// --------------
		Finance::AssignSubtotal($sWhere);
		// --------------

		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('UserAccountLog',array(
		'where'=>$sWhere,
		));

		//		$oTable->sSql="select u.*,uc.*, uc.name as customer_name, ual.*
		//						,ua.amount as current_account_amount
		//						, ualt.name as user_account_log_type_name
		//					from user_account_log as ual
		//					inner join user as u on ual.id_user=u.id
		//					inner join user_customer as uc on uc.id_user=u.id
		//					inner join user_account as ua on ua.id_user=u.id
		//					left join user_account_log_type as ualt on ual.id_user_account_log_type=ualt.id
		//					where 1=1
		//						and ual.id_user in (".$this->sCustomerSql.")
		//						".$sWhere;
		$_SESSION['finance']['current_sql']=$oTable->sSql;

		$oTable->aOrdered="order by ual.id desc";
		$oTable->aColumn=array(
		'login'=>array('sTitle'=>'Customer Login'),
		'account_amount'=>array('sTitle'=>'AccountAmount/DebtAmount'),
		'debet'=>array('sTitle'=>'finance debet'),
		'credit'=>array('sTitle'=>'finance credit'),
		'custom_id'=>array('sTitle'=>'Ual CustomId'),
		'post'=>array('sTitle'=>'Date'),
		'description'=>array('sTitle'=>'Description'),
		);
		$oTable->sDataTemplate='manager/row_finance.tpl';
		$oTable->sButtonTemplate='manager/button_finance.tpl';
		$oTable->sSubtotalTemplate='manager/subtotal_finance.tpl';
		$oTable->aCallback=array($this,'CallParseLog');

		$sTable=$oTable->getTable("Account Log",'customer_account_log');


		if (Base::$aRequest['search']['login'] || Base::$aRequest['search']['custom_id']) {
			if (Base::$aRequest['search']['custom_id']) {
				$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['search']['custom_id'])));

				$aCustomer=Db::GetRow(Db::GetSql('Customer',array(
				'id'=>($aCartPackage['id_user'] ? $aCartPackage['id_user']:'-1'),
				)));
			}
			elseif (Base::$aRequest['search']['login']) {
				$aCustomer=Db::GetRow(Db::GetSql('Customer',array('login'=>Base::$aRequest['search']['login'])));
			}

			if ($aCustomer) {
				Base::$tpl->assign('aCustomer',$aCustomer);
				Base::$tpl->assign('aCartPackage',$aCartPackage);
				
				$aField['login']=array('title'=>'Login','type'=>'text','value'=>$aCustomer['login']);
				$aField['email']=array('title'=>'Email','type'=>'text','value'=>$aCustomer['email']);
				$aField['phone']=array('title'=>'Phone','type'=>'text','value'=>$aCustomer['phone']);
				$aField['group_discount']=array('title'=>'GDiscount','type'=>'text','value'=>$aCustomer['group_discount'].' %');
				$aField['customer_group_name']=array('title'=>'Group','type'=>'text','value'=>$aCustomer['customer_group_name']);
				$aField['amount']=array('title'=>'Amount','type'=>'text','value'=>Language::PrintPrice($aCustomer['amount']));
				$aField['discount_static']=array('title'=>'DiscountStatic','type'=>'text','value'=>$aCustomer['discount_static'].' %');
				$aField['discount_dynamic']=array('title'=>'DiscountDinamic','type'=>'text','value'=>$aCustomer['discount_dynamic'].' %');
				$aField['deposit_money']=array('type'=>'button','class'=>'at-btn','value'=>'Deposit money','onclick'=>"location.href='/?action=manager_finance_add &id_user=".$aCustomer['id'].($aCartPackage?"&custom_id=".$aCartPackage['id']:" ")."&login=".$aCustomer['login']." &return=".urldecode($sReturn)."'");
				
				$aData=array(
				//'sContent'=>Base::$tpl->fetch('manager/form_finance_login.tpl'),
				'aField'=>$aField,
				'bType'=>'generate',
				'sWidth'=>'90%',
				);
				$oForm=new Form($aData);
				Base::$sText.=$oForm->getForm();
			}
		}

		/**
		 * Moved bottom from table for $sReturn generation
		 */
		Base::$sText.=$sTable;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseLog(&$aItem)
	{
		$aCustomerDebt=Base::$db->GetAll(Base::GetSql('CustomerDebt'));
		$aCustomerDebtHash=Language::Array2Hash($aCustomerDebt,'id_user');
		//Base::$tpl->assign('aCustomerDebtHash',$aCustomerDebtHash);
		$aIdCustomer=array();
		if ($aItem) foreach($aItem as $key => $value) {
			$aItem[$key]['current_debt_amount']=$aCustomerDebtHash[$value['id_user']]['amount'];
			if (!in_array($value['id_user'],$aIdCustomer)) $aIdCustomer[]=$value['id_user'];

			if ($value['custom_id']>0) $aCustomId[]=$value['custom_id'];
		}

		$aCustomerManagerHash=Base::$db->GetAssoc(Base::GetSql('Customer/ManagerAssoc',array('id_user_array'=>$aIdCustomer)));
		if ($aCustomId) {
			$aDebtCartAssoc=Db::GetAssoc('Assoc/Debt',array('where'=>" and ld.is_payed='0'
				and custom_id in (".(implode(',',$aCustomId)).")"));
		}
		if ($aItem) foreach($aItem as $sKey => $aValue) {
			$aItem[$sKey]['manager_login']=$aCustomerManagerHash[$aValue['id_user']];
			$aItem[$sKey]['debt_cart_unpaid']=$aDebtCartAssoc[$aValue['custom_id']];
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ReturnFullPaymentDiscount($aCart)
	{
		if ($aCart['full_payment_discount']>0) {
			Finance::Deposit($aCart['id_user'],-$aCart['full_payment_discount']
			,Language::getMessage('Return full payment discount for cart #').$aCart['id']
			,$aCart['id_cart_package'],'internal','cart','',3);
			Base::$db->Execute("update cart set full_payment_discount='0' where id='{$aCart['id']}'");
			$aCart['full_payment_discount']=0;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function FinanceExportAll()
	{
		$sFileName=Finance::CreateFinanceExcel($_SESSION['finance']['current_sql'].' order by ual.id desc',true);
		Base::$tpl->assign('sFileName',$sFileName);

		Base::$sText.=Base::$tpl->fetch('manager/export_finance.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function ParentMarginDebet($aCart)
	{
		/**
		 * Unused
		 */
		//		if ($aCart['is_parent_margin_debeted']) return;
		//		$aUser=Base::$db->getRow(Base::GetSql('Customer',array('id'=>$aCart['id_user'],)));
		//		if ($aUser['is_test'] || !$aUser['id_parent']) return;
		//
		//		$dDebetAmount=$aCart['price_parent_margin']*$aCart['number'];
		//		if ($dDebetAmount>0) {
		//			$iInsertedId=Finance::Deposit($aUser['id_parent'],$dDebetAmount
		//			,Language::getMessage('Parent margin debet cart #').$aCart['id'].' - '.$aUser['login']
		//			,$aCart['id'],'internal','cart','',10);
		//
		//			//			InvoiceAccountLog::Add($aUser['id_parent'],$iInsertedId,'user_account_log',$dDebetAmount);
		//		}
		//
		//		$dDebetAmountSecond=$aCart['price_parent_margin_second']*$aCart['number'];
		//		if ($dDebetAmountSecond>0) {
		//			$iInsertedId=Finance::Deposit($aUser['id_parent_second'],$dDebetAmountSecond
		//			,Language::getMessage('Parent margin debet cart #').$aCart['id'].' - '.$aUser['login']
		//			,$aCart['id'],'internal','cart','',10);
		//
		//			/*InvoiceAccountLog::Add($aUser['id_parent_second'],$iInsertedId,'user_account_log',$dDebetAmountSecond);*/
		//		}
		//
		//		Base::$db->Execute("update cart set is_parent_margin_debeted='1' where id='{$aCart['id']}'");
	}
	//-----------------------------------------------------------------------------------------------
	public function IsChangeableLogin($sLogin)
	{
		//return Customer::IsChangeableLogin($sLogin);
		return Customer::IsTempUser($sLogin);
	}
	//-----------------------------------------------------------------------------------------------
	public function CountMoney()
	{
		if (Base::$aRequest['is_post']) {

			$sWhere="
			and cl.post_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['count_date_from'])."'
			and cl.post_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['count_date_to'])."'
			";

			$sVinWhere=" and c.login_vin_request = '".Auth::$aUser['login']."'";
			$sVinSumSql=Base::GetSql('Manager/CountMoney',array('where'=>$sVinWhere.$sWhere));

			$sDiscountWhere=" and cg.price_type='discount' and uc.id_manager='".Auth::$aUser['id']."'";
			if (Base::$aRequest['search']['code_customer_group_discount'])
			$sDiscountWhere.=" and cg.code='".Base::$aRequest['search']['code_customer_group_discount']."'";
			$sDiscountSumSql=Base::GetSql('Manager/CountMoney',array('where'=>$sDiscountWhere.$sWhere));

			$sMarginWhere=" and cg.price_type='margin' and uc.id_manager='".Auth::$aUser['id']."'";
			if (Base::$aRequest['search']['code_customer_group_margin'])
			$sMarginWhere.=" and cg.code='".Base::$aRequest['search']['code_customer_group_margin']."'";
			$sMarginSumSql=Base::GetSql('Manager/CountMoney',array('where'=>$sMarginWhere.$sWhere));

			Base::$oResponse->AddAssign('manager_vin_money','innerHTML',Language::PrintPrice(Db::GetOne($sVinSumSql)));
			Base::$oResponse->AddAssign('manager_discount_money','innerHTML', Language::PrintPrice(Db::GetOne($sDiscountSumSql)));
			Base::$oResponse->AddAssign('manager_margin_money','innerHTML', Language::PrintPrice(Db::GetOne($sMarginSumSql)));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PayCartPackage($iIdCartPackage,$aEntry=array())
	{
		if ($iIdCartPackage) {
			$aCartPackageUpdate=array(
			'is_payed'=>1,
			);

			$sOrderStaus=Db::GetOne("select order_status from cart_package where id='".$iIdCartPackage."'");
			if ($sOrderStaus=="pending") $aCartPackageUpdate['order_status']='work';

			Db::AutoExecute('cart_package',$aCartPackageUpdate,'UPDATE',"id='".$iIdCartPackage."'");
			Manager::SetPriceTotalCartPackage(array('id_cart_package'=>$iIdCartPackage));

			Cart::SendPendingWork($iIdCartPackage);

			Manager::NotifyDebitedMoney($aEntry);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function NotifyDebitedMoney($aEntry=array())
	{
		if ($aEntry && $aEntry['id_buh_credit']=361)
		{
			$aIdBuhDebit=explode(",",Base::GetConstant('buh:id_buh_debit_money','302,301'));
			if ($aIdBuhDebit && in_array($aEntry['id_buh_debit'],$aIdBuhDebit)) {
				$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
				'id'=>$aEntry['id_buh_credit_subconto1'],
				)));

				$aManager=Db::GetRow(Base::GetSql('Manager',array(
				'id'=>$aCustomer['id_manager'],
				)));

				Message::CreateDelayedNotification($aCustomer['id'],'notified_debited_money'
				,array('aEntry'=>$aEntry,'aCustomer'=>$aCustomer,'aManager'=>$aManager),true);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintOrder()
	{
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array(
		'where'=>"and cp.id='".Base::$aRequest['id']."'"))); //and cp.id_user='".Auth::$aUser['id']."'
		$aUserCart=Db::GetAll(Base::GetSql("Part/Search",array(
		"where"=>" and c.id_cart_package='".Base::$aRequest['id']."' and c.type_='order' ".
		"and c.order_status != 'refused' " //and c.id_user='".Auth::$aUser['id']."'
		)));

		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
		'id'=>(Base::$aRequest['id_user'] ? Base::$aRequest['id_user'] : -1),
		)));

		if (!$aUserCart || !$aCartPackage) Base::Redirect('?action=cart_package&table_error=cart_package_not_found');

		$aActiveAccount=Db::GetRow(Base::GetSql('Account',array('is_active'=>1)));

		$sPriceTotalString=Currency::CurrecyConvert(Currency::BillRound($aCartPackage['price_total']),
		Base::GetConstant('global:base_currency'));
		$sPriceTotalString=String::GetUcfirst(trim($sPriceTotalString));

		$aCartPackage['price_total_string']=$sPriceTotalString;

		Base::$tpl->assign('aActiveAccount',$aActiveAccount);
		Base::$tpl->assign('aUserCart',$aUserCart);
		Base::$tpl->assign('aCartPackage',$aCartPackage);
		Base::$tpl->assign('aCustomer',$aCustomer);
		//Base::$tpl->assign('sMirautoInfo',Language::GetText('mirauto_info'));

		PrintContent::Append(Base::$tpl->fetch('cart/package_print.tpl'));
		Base::Redirect('?action=print_content&return=manager_package_list');
	}
	//-----------------------------------------------------------------------------------------------
	public function RefusePending()
	{
		$aCart=Db::GetRow(Base::GetSql('Cart',array(
		'id'=> Base::$aRequest['id'],
		'status_array'=> array("'pending'"),
		)));
		if (!$aCart) Form::RedirectAuto("&aMessage[MI_NOTICE]=no such cart");

		$aMessage = $this->ProcessOrderStatus($aCart['id'],'refused');
		if (!$aMessage)
			Form::RedirectAuto("&aMessage[MT_NOTICE]=order refused");
		else 
			Form::RedirectAuto("&aMessage[MT_NOTICE_NT]=".$aMessage);
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintPakage() {
		if (Base::$aRequest['row_check'])
		{
			$oCart= new Cart(false);
			//$sFile=$oCart->PrintPackageExcel(Base::$aRequest['row_check']);        PrintPackageExcel()-is`n in code. ExportOrderAll() is simple implement of PrintPackageExcel();

			Base::$aRequest['search']['id']=Base::$aRequest['row_check'];
			$sFile=Manager::ExportOrderAll();
			if ($sFile) $sMessage="&aMessage[MT_NOTICE]=<a href=".$oCart->sPathToFile.$sFile.">Download files</a>";
			else $sMessage="&aMessage[MT_ERROR]=Export Faild";
		} else $sMessage="&aMessage[MT_ERROR]=Check Package";

		Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	static function SetPriceTotalCartPackage($aCart) {
		if ($aCart['id_cart_package']){
			$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
				
			$aUserCart=Db::GetAll("select * from cart
				where id_cart_package='".$aCart['id_cart_package']."' and order_status<>'refused' and order_status<>'removed'");
			
			if (!$aUserCart) return;
				
			foreach ($aUserCart as $iKey => $aValue)
				$dSum+=Currency::PrintPrice($aValue['price'],$iId_GeneralCurrencyCode,2,"<none>")*$aValue['number'];
				
			/*$dSum=Db::GetOne("select sum(number*price) from cart
			 where id_cart_package='".$aCart['id_cart_package']."' and order_status<>'refused'");
			*/

			$dAmount=Db::GetOne("select sum(amount) as amountsum
				from user_account_log
				where section='internal' and custom_id=".$aCart['id_cart_package']);

			$dDiscountTotal=round($dSum*$aCart['discount']/100,2);

			if ($dSum) {
				$sSql="update cart_package set price_total=".$dSum."-".$dDiscountTotal."+price_delivery";
				if ($dAmount) $sSql.=", price_additional=".$dSum."-".$dDiscountTotal."+price_delivery-".$dAmount;
				$sSql.=", discount_total=".$dDiscountTotal." where id='".$aCart['id_cart_package']."'";
			} else {
				$sSql="update cart_package set price_total=0, price_additional=0 where id='".$aCart['id_cart_package']."'";
			}
			Db::Execute($sSql);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function MergePakage() {
		if (Base::$aRequest['id'])
		{
			$aCart=Db::GetAll(Base::GetSql("Cart",array('id_cart_package'=>Base::$aRequest['id'])));

			if ($aCart) {
				$aCartPackage=Db::GetRow(Base::GetSql("CartPackage",array(
				'id_user'=>$aCart[0]['id_user'],
				'order_status'=>'work',
				))." order by id desc"
				);

				foreach ($aCart as $sKey => $aValue) {
					Db::Execute("update cart
					set id_cart_package=".$aCartPackage['id']."
					where id=".$aValue['id']);
				}
				$this->SetPriceTotalCartPackage(array('id_cart_package'=>$aCartPackage['id']));

				Db::Execute("update user_account_log
					set custom_id=".$aCartPackage['id']." , description= concat(description,' merge ".Base::$aRequest['id']."')
					where custom_id=".Base::$aRequest['id']);

				Db::Execute("update cart_package
					set is_payed=0, manager_comment=concat(manager_comment,' merge to ".$aCartPackage['id']."')
					where id=".Base::$aRequest['id']);


				$sMessage="&aMessage[MT_NOTICE]=Merge successful";
			} else $sMessage="&aMessage[MT_ERROR]=Empty Cart";
		} else $sMessage="&aMessage[MT_ERROR]=Check Package";

		Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeProvider () {
		require(SERVER_PATH.'/include/order_status_config.php');
		
		Auth::NeedAuth('manager');
		Base::$tpl->assign('aProvider',$aProvider=Db::GetAssoc("Assoc/UserProvider"));

		/* [ apply  */
		if (Base::$aRequest['is_post'])
		{
			$aData=String::FilterRequestData(Base::$aRequest['data']);
			if ($aData['id_provider_ordered']) {
				$aDataCart=Db::GetRow(Base::GetSql('Part/Search',array(
						'id_cart'=> Base::$aRequest['id_cart']
				)));
				if ($aDataCart && !(in_array($aDataCart['order_status'],$aAllowChangeProviderDetailStatus)))
					Base::Redirect('/pages/manager_order');
				
				$aData['provider_name']=Db::GetOne("select name from user_provider where id_user=".$aData['id_provider_ordered']);
				$aData['id_provider']=$aData['id_provider_ordered'];
				$aData['provider_name_ordered']=Db::GetOne("select name from user_provider where id_user=".$aData['id_provider_ordered']);
				$aData['comment']='changed provider';
				unset($aData['id_provider_ordered']);
				Db::AutoExecute("cart",$aData,"UPDATE","id=".Base::$aRequest['id_cart']);
				// log
				$sComment=mysql_escape_string(Language::getMessage('changed provider').': '.$aDataCart['provider_name'].' ('.$aDataCart['id_provider'].') => '.$aData['provider_name'].' ('.$aData['id_provider'].')');
				Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager,is_customer_visible)
					values ('".$aDataCart['id']."',UNIX_TIMESTAMP(),'".$aDataCart['order_status']."','$sComment',".Auth::$aUser["id"].",0)");
				
				$sMessage="&aMessage[MT_NOTICE]=changed";
			}
			Form::RedirectAuto($sMessage);

		}
		/* ] apply */

		if (Base::$aRequest['action']==$this->sPrefix.'_change_provider')
		{
			Base::$tpl->assign('aData',$aData=Db::GetRow(Base::GetSql('Part/Search',array(
			'id_cart'=> Base::$aRequest['id_cart']
			))));
			if ($aData && !(in_array($aData['order_status'],$aAllowChangeProviderDetailStatus)))
				Base::Redirect('/pages/manager_order');

			$aField['provider_name']=array('title'=>'Provider','type'=>'input','value'=>$aData['provider_name'],'readonly'=>1);
			$aField['id_provider_ordered']=array('title'=>'Order to the provider','type'=>'select','options'=>$aProvider,'selected'=>$aData['id_provider_ordered'],'name'=>'data[id_provider_ordered]','szir'=>1);
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Order to the provider",
			//'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_order_provider.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>$this->sPrefix."_change_provider",
			'sReturnButton'=>'<< Return',
			'bAutoReturn'=>true,
			'sWidth'=>"500px",
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();

			return;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SetPackagePayed () {
		if(Base::$aRequest['id']){
			$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array(
			'id'=> Base::$aRequest['id']
			)));
		}
		if(!$aCartPackage['id']){
			$sMessage="&aMessage[MT_ERROR]=not found";
		}else{
			Db::AutoExecute("cart_package",array('is_payed'=>1),"UPDATE","id='".$aCartPackage['id']."'");
			$sMessage="&aMessage[MT_NOTICE]=changed";
		}
		Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	public function Cat () {
		if (Base::$aRequest['is_post'])
		{
			Base::$aRequest['data']['pref']=substr(mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '', trim(Content::Translit(Base::$aRequest['data']['pref']))),'UTF-8'),0,3);
			if (Base::$aRequest['data']['id']) $sWhere=" and id<>'".Base::$aRequest['data']['id']."'";
			$bExist=Db::GetOne("select count(*) from cat where pref='".Base::$aRequest['data']['pref']."' ".$sWhere);
			Base::$aRequest['data']['name']=mb_strtoupper(str_replace(array(' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\','<','>','?','!','$','%','^','@','~','|','=',';','{','}','№'), '', trim(Content::Translit(Base::$aRequest['data']['name']))),'UTF-8');			
			
			if ($bExist){
				Form::ShowError("pref already exists");
				Base::$aRequest['action']='manager_cat_add';
				Base::$tpl->assign('aData',Base::$aRequest['data']);
			} elseif (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['title']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_cat_add';
				Base::$tpl->assign('aData',Base::$aRequest['data']);
			}
			else {
				$aData=String::FilterRequestData(Base::$aRequest['data']);
				$aData['descr']=Base::$aRequest['data']['descr'];
				if($aData['id']){
					Db::AutoExecute('cat',$aData,'UPDATE',"id='".$aData['id']."'");
					Form::RedirectAuto("&aMessage[MI_NOTICE]=updated");
				}else{
					Db::AutoExecute('cat',$aData);
					Form::RedirectAuto("&aMessage[MI_NOTICE]=added");
				}
			}
		}

		if (Base::$aRequest['action']=='manager_cat_add') {
			Resource::Get()->Add('/libp/ckeditor/ckeditor.js');
			Resource::Get()->Add('/libp/ckeditor/config.js');

			if(Base::$aRequest['id'] && !Base::$aRequest['data']['id']){
				Base::$tpl->assign('aData',$aData=Db::GetRow(Base::GetSql('Cat',array('id'=>Base::$aRequest['id']))));
			}

			$aField['name']=array('title'=>'Name','type'=>'input','value'=>$aData['name'],'name'=>'data[name]','szir'=>1);
			$aField['pref']=array('title'=>'Pref','type'=>'input','value'=>$aData['pref'],'name'=>'data[pref]','szir'=>1,'contexthint'=>'catalog_pref');
			$aField['title']=array('title'=>'Title','type'=>'input','value'=>$aData['title'],'name'=>'data[title]','szir'=>1);
			$aField['link']=array('title'=>'link','type'=>'input','value'=>$aData['link'],'name'=>'data[link]');
			$aField['country']=array('title'=>'country','type'=>'input','value'=>$aData['country'],'name'=>'data[country]');
			$aField['description']=array('title'=>'Description','type'=>'textarea','name'=>'data[description]','value'=>$aData['description']);
			$aField['descr']=array('title'=>'Descr','type'=>'textarea','name'=>'data[descr]','value'=>$aData['descr'],'class'=>'ckeditor');
			$aField['id_sup']=array('title'=>'id_sup','type'=>'input','value'=>$aData['id_sup'],'name'=>'data[id_sup]');
			$aField['id_mfa']=array('title'=>'id_mfa','type'=>'input','value'=>$aData['id_mfa'],'name'=>'data[id_mfa]');
			$aField['is_brand_hidden']=array('type'=>'hidden','name'=>'data[is_brand]','value'=>'0');
			$aField['is_brand']=array('title'=>'is_brand','type'=>'checkbox','name'=>'data[is_brand]','value'=>'1','checked'=>$aData['is_brand']);
			$aField['is_main_hidden']=array('type'=>'hidden','name'=>'data[is_main]','value'=>'0');
			$aField['is_main']=array('title'=>'is_main','type'=>'checkbox','name'=>'data[is_main]','value'=>'1','checked'=>$aData['is_main']);
			$aField['visible_hidden']=array('type'=>'hidden','name'=>'data[visible]','value'=>'0');
			$aField['visible']=array('title'=>'visible','type'=>'checkbox','name'=>'data[visible]','value'=>'1','checked'=>$aData['visible']);
			$aField['id']=array('type'=>'hidden','name'=>'data[id]','value'=>$aData['id']);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cat add",
			//'sContent'=>Base::$tpl->fetch('manager/form_cat.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_cat_add',
			'sWidth'=>'100%',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();
			return;
		}
        unset($aField);
		$aField['name']=array('title'=>'Name','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
		$aField['pref']=array('title'=>'Pref','type'=>'input','value'=>Base::$aRequest['search']['pref'],'name'=>'search[pref]');
		$aField['title']=array('title'=>'Title','type'=>'input','value'=>Base::$aRequest['search']['title'],'name'=>'search[title]');
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager/form_cat_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_cat',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sWidth'=>'700px',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();



		// --- search ---
		if (Base::$aRequest['search']['name']) $sWhere.=" and c.name like '".Base::$aRequest['search']['name']."%'";
		if (Base::$aRequest['search']['pref']) $sWhere.=" and c.pref = '".Base::$aRequest['search']['pref']."'";
		if (Base::$aRequest['search']['title']) $sWhere.=" and c.title like '".Base::$aRequest['search']['title']."%'";

		// --------------

		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('Cat',array(
		'where'=>$sWhere,
		));

		$oTable->aColumn=array(
		'name'=>array('sTitle'=>'Name'),
		'pref'=>array('sTitle'=>'Pref'),
		'title'=>array('sTitle'=>'Title'),
		'visible'=>array('sTitle'=>'visible'),
		'is_brand'=>array('sTitle'=>'is_brand'),
		'is_main'=>array('sTitle'=>'is_main'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='manager/row_cat.tpl';
		$oTable->sButtonTemplate='manager/button_cat.tpl';

		$sTable=$oTable->getTable("ManagerCat");


		Base::$sText.=$sTable;
	}
	//-----------------------------------------------------------------------------------------------
	public function SynonymBrandClear ($sBrand) {
		$sBrand=  str_replace(' ', '', $sBrand);
		$sBrand=  str_replace('&', '', $sBrand);
		return $sBrand;
	}
	//-----------------------------------------------------------------------------------------------
	public function Synonym () {
		if(Base::$aRequest['xajax']){
			if(Base::$aRequest['new_brand']){
				$sPrefNew=Db::GetOne("select pref from cat where id='".Base::$aRequest['cat_id']."'");
				if(!$sPrefNew){
					Base::$oResponse->addAlert(Language::GetMessage("mansyn_not_found_new_pref"));
					return;
				}
				$sMain=Db::GetOne("select pref from cat where title='".Base::$aRequest['new_brand']."'");
				if($sMain){
					$iPrice=Db::GetOne("select count(*) from price where pref='".$sMain."'");
					if($iPrice){
						Db::Execute("update price set pref='".$sPrefNew."'
							,item_code=replace(item_code,'".$sMain."_','".$sPrefNew."_') 
							where pref='".$sMain."'");
					}
					$iCart=Db::GetOne("select count(*) from cart where pref='".$sMain."'");
					if($iCart){
						Db::Execute("update cart set pref='".$sPrefNew."'
							,item_code=replace(item_code,'".$sMain."_','".$sPrefNew."_') 
							where pref='".$sMain."'");
					}
					if($iPrice>0 || $iCart>0){
						$s='';
						if($iPrice>0) $s.=' '.Language::GetMessage("mansyn_changed_price");
						if($iCart>0) $s.=' '.Language::GetMessage("mansyn_changed_cart");
						Base::$oResponse->addAlert(Language::GetMessage("mansyn_main_brand").$s);
					}
					$iMain=Db::GetOne("select id from cat where title='".Base::$aRequest['new_brand']."'");
					Db::Execute("update cat_pref set cat_id='".Base::$aRequest['cat_id']."' 	where cat_id='".$iMain."'");
					Db::Execute("delete from cat where pref='".$sMain."'");
					//Base::$oResponse->addAlert("Это основной бренд, его нельзя добавлять");
					//return;
				}
				$sMain=Db::GetOne("select c.title from cat_pref cp inner join cat c on c.id=cp.cat_id and cp.name='".Base::$aRequest['new_brand']."'");
				if($sMain){
					Base::$oResponse->addAlert("Выбранный бренд уже привязан к ".$sMain);
					return;
				}
				Db::Execute("update cat_pref set cat_id='".Base::$aRequest['cat_id']."' where name='".Base::$aRequest['new_brand']."'");
				Base::$aRequest['brand']=Db::GetOne("select title from cat where id='".Base::$aRequest['cat_id']."'");
			}
			if(Base::$aRequest['brand']){
				$sMain=Db::GetOne("select title from cat where title='".Base::$aRequest['brand']."'");
				if(!$sMain) $sMain=Db::GetOne("select c.title from cat c
					inner join cat_pref cp on c.id=cp.cat_id and cp.name='".Base::$aRequest['brand']."'");
				if(Base::$aRequest['delete']==2){
					$i=DB::GetOne("select id from cat where title='".Base::$aRequest['brand']."'");
					if ($i) {
						$sPref = DB::GetOne("select pref from cat where id=".$i);
						Db::Execute("delete from cat_pref where cat_id='".$i."'");
						Db::Execute("delete from cat where title='".Base::$aRequest['brand']."'");
						Db::Execute("delete from cat_part where pref='".$sPref."'");
						Db::Execute("delete from price where pref='".$sPref."'");

						Db::Execute("delete from `price_group_assign` where pref='".$sPref."'");
						Db::Execute("DELETE cpw FROM cat_part_weight AS cpw INNER JOIN cat_part cp ON cp.id = cpw.id_cat_part AND cp.pref='".$sPref."'");
						Db::Execute("DELETE pi FROM cat_pic AS pi INNER JOIN cat_part cp ON cp.id = pi.id_cat_part AND cp.pref='".$sPref."'");
						Db::Execute("delete from cat_cross where pref='".$sPref."'");
						Db::Execute("delete from cat_cross where pref_crs='".$sPref."'");
						Db::Execute("delete from cat_cross_stop where pref='".$sPref."'");
						Db::Execute("delete from cat_cross_stop where pref_crs='".$sPref."'");
						Db::Execute("delete from cart where type_='cart' and pref='".$sPref."'");
						Db::Execute("delete from cart_deleted where pref='".$sPref."'");
			
					}
					Base::$oResponse->addScript("location.reload();");
					return;
				}
				if(Base::$aRequest['delete']){
					Db::Execute("delete from cat_pref where name='".Base::$aRequest['brand']."'");
					if(!$sMain){
						Base::$oResponse->addScript("location.reload();");
						return;
					}
				}
				if($sMain){
					Base::$tpl->assign('iCatId',Db::GetOne("select id from cat where title='".$sMain."'"));
					$aSynonym0=array(array(
					'name'=>$sMain,
					'is_main'=>1,
					));
					$aSynonym1=Db::GetAll(
					$s="select cp.name,0 is_main from cat c"
					. " left join cat_pref cp on cp.cat_id=c.id"
					. " where c.title='".$sMain."'"
					. " order by cp.name"
					);
					$aSynonym=  array_merge($aSynonym0,$aSynonym1);
				}
				if($aSynonym){
					Base::$oResponse->addScript("$('.synonym-plus').show();");
					foreach ($aSynonym as $value) {
						$sBrand=  Manager::SynonymBrandClear($value['name']);
						Base::$oResponse->addScript("$('.synonym-plus_".$sBrand."').hide();");
					}
				}else
				Base::$oResponse->addScript("$('.synonym-plus').hide();");
				if(!$aSynonym)
				$aSynonym=Db::GetAll(
					$s="select cp.name,0 is_main from cat_pref cp "
					. " where cp.name='".Base::$aRequest['brand']."'"
					. " order by cp.name"
					);
				//Debug::PrintPre($aSynonym);
				Base::$tpl->assign('aSynonym',$aSynonym);
				Base::$oResponse->addAssign('id_synonym','outerHTML',Base::$tpl->fetch("manager/select_synonym.tpl"));
				$sBrand=  Manager::SynonymBrandClear(Base::$aRequest['brand']);
				Base::$oResponse->addScript("$('#div_brand').scrollTo($('#brand_".$sBrand."'), 10);");
			}
			if(isset(Base::$aRequest['search'])){
				$s=Db::GetOne("select id from ("
				. " select replace(replace(title,'&',''),' ','') as id,title as name from cat"
				. " union "
				. " select replace(replace(name,'&',''),' ','') as id, name from cat_pref /*where cat_id>0*/"
				. " ) t"
				. " where t.name like '".Base::$aRequest['search']."%' order by t.name");
				Base::$oResponse->addScript("$('#div_brand').scrollTo($('#brand_".$s."'), 500);");
			}
			return;
		}
		Base::$tpl->assign('aBrand',Db::GetAssoc("select id,name from ("
				. " select replace(replace(title,'&',''),' ','') as id,title as name from cat"
				. " union "
				. " select replace(replace(name,'&',''),' ','') as id, name from cat_pref /*where cat_id>0*/"
				. " ) t"
				. " order by t.name"));
		Base::$tpl->assign('aSynonym',array(-1=>array('name'=>Language::GetMessage('Choose brand first'))));
		Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'manager_synonym');
		Base::$sText.=Base::$tpl->fetch('manager/synonym.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function CatPref () {
		Base::$aTopPageTemplate=array('panel/tab_price.tpl'=>'manager_cat_pref');
		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['data']['name'] || !Base::$aRequest['data']['cat_id']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_cat_pref_add';
				Base::$tpl->assign('aData',Base::$aRequest['data']);
			}
			else {
				$aData=String::FilterRequestData(Base::$aRequest['data']);
				if($aData['id']){
					Db::AutoExecute('cat_pref',$aData,'UPDATE',"id='".$aData['id']."'");
					Form::RedirectAuto("&aMessage[MI_NOTICE]=updated");
				}else{
					Db::AutoExecute('cat_pref',$aData);
					Form::RedirectAuto("&aMessage[MI_NOTICE]=added");
				}
			}
		}

		if (Base::$aRequest['action']=='manager_cat_pref_add') {
			if(Base::$aRequest['id'] && !Base::$aRequest['data']['id']){
				Base::$tpl->assign('aData',$aData=Db::GetRow(Base::GetSql('CatPref',
				array(
					'id'=>Base::$aRequest['id'],
					'is_left'=> true,
				))));
			}
			Base::$tpl->assign('aPrefAssoc',$aPrefAssoc=array(""=>"")+Db::GetAssoc("select id, concat(title,' [',pref,']') name from cat order by title"));
			
			$sReturn= trim(strip_tags(Base::$aRequest['return'])) ? trim(strip_tags(Base::$aRequest['return'])) : 'manager_cat_pref';
			$sReturn = str_replace('action=','',$sReturn);

			$aField['name']=array('title'=>'Name','type'=>'input','value'=>$aData['name'],'name'=>'data[name]','szir'=>1);
			$aField['cat_id']=array('title'=>'Pref','type'=>'select','options'=>$aPrefAssoc,'selected'=>$aData['cat_id'],'name'=>'data[cat_id]','szir'=>1);
			$aField['id']=array('type'=>'hidden','name'=>'data[id]','value'=>$aData['id']);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Catpref add",
			//'sContent'=>Base::$tpl->fetch('manager/form_cat_pref.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_cat_pref_add',
		    'sReturnButton'=>'return',
		    'sReturnAction'=>$sReturn,
			//'sWidth'=>'750px',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();
			return;
		}

		if (Base::$aRequest['action']=='manager_cat_pref_delete') {
			$aPref = Db::getRow("Select cat.pref,cat.id from cat_pref cp
				inner join cat on cp.cat_id = cat.id
				where cp.id=".Base::$aRequest['id']);
			Db::Execute("delete from cat_pref where id='".Base::$aRequest['id']."'");
			if ($aPref) {
				Db::Execute("delete from cat where pref='".$aPref['pref']."'");
				Db::Execute("delete from cat_pref where cat_id='".$aPref['id']."'");
				Db::Execute("delete from cat_part where pref='".$aPref['pref']."'");
				Db::Execute("delete from price where pref='".$aPref['pref']."'");

				Db::Execute("delete from `price_group_assign` where pref='".$aPref['pref']."'");
				Db::Execute("DELETE cpw FROM cat_part_weight AS cpw INNER JOIN cat_part cp ON cp.id = cpw.id_cat_part AND cp.pref='".$aPref['pref']."'");
				Db::Execute("DELETE pi FROM cat_pic AS pi INNER JOIN cat_part cp ON cp.id = pi.id_cat_part AND cp.pref='".$aPref['pref']."'");
				Db::Execute("delete from cat_cross where pref='".$aPref['pref']."'");
				Db::Execute("delete from cat_cross where pref_crs='".$aPref['pref']."'");
				Db::Execute("delete from cat_cross_stop where pref='".$aPref['pref']."'");
				Db::Execute("delete from cat_cross_stop where pref_crs='".$aPref['pref']."'");
				Db::Execute("delete from cart where type_='cart' and pref='".$aPref['pref']."'");
				Db::Execute("delete from cart_deleted where pref='".$aPref['pref']."'");
			}
			Base::Message(array('MI_NOTICE'=>'deleted'));
		}

		$aField['name']=array('title'=>'Name','type'=>'input','value'=>Base::$aRequest['search']['name'],'name'=>'search[name]');
		$aField['pref']=array('title'=>'Pref','type'=>'input','value'=>Base::$aRequest['search']['pref'],'name'=>'search[pref]');
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager/form_cat_pref_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_cat_pref',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sWidth'=>'700px',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();



		// --- search ---
		if (Base::$aRequest['search']['name']) $sWhere.=" and cp.name like '".Base::$aRequest['search']['name']."%'";
		if (Base::$aRequest['search']['pref']) $sWhere.=" and c.pref = '".Base::$aRequest['search']['pref']."'";

		// --------------

		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('CatPref',array(
		'where'=>$sWhere,
		'is_left'=> true,
		));

		$oTable->aColumn=array(
		'name'=>array('sTitle'=>'Name'),
		'pref'=>array('sTitle'=>'Pref'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='manager/row_cat_pref.tpl';
		$oTable->sButtonTemplate='manager/button_cat_pref.tpl';

		$sTable=$oTable->getTable("ManagerCatPref");


		Base::$sText.=$sTable;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetCheckedAuto () {
		if (Base::$aRequest['id'] && isset(Base::$aRequest['val']) ) {
			$aMass = explode("_",Base::$aRequest['id']);
			$aOrder = Db::GetRow("Select * from cart_package where id=".$aMass[1]);
			if ($aOrder['id']) {
				// need set checked
				if ($aOrder['is_need_check'] == 1) 
					Db::Execute("Update cart_package set is_checked_auto=".Base::$aRequest['val']." where id=".$aOrder['id']);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CustomerRecalcCart () {
		if (Base::$aRequest['id']) {
			User::RecalcCart(Base::$aRequest['id'],1);
			// rebuild order items - code from cart CartOnePageOrder
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
			Base::$tpl->assign('dSubtotal',$dSubtotal);
			Base::$tpl->assign('dTotal',$dSubtotal+Currency::PrintPrice($_SESSION['current_cart']['price_delivery']));
			
			Base::$oResponse->addAssign('text_order','innerHTML',
			Base::$tpl->fetch("cart/text_order.tpl"));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function JoinOrders() {
		if (Base::$aRequest['row_check']) {
			// check diff owners orders
			$aCnt = Db::GetAll("Select count(*) as cnt, id_user from cart_package where id in ('".implode("','",Base::$aRequest['row_check'])."') group by id_user");
			if (count($aCnt) > 1)
				$sMessage = "&aMessage[MT_ERROR]=Great 1 Check Package";
			else {
				// check diff user auto
				$aAutoCnt = Db::GetAll("Select id_own_auto from cart_package where id_own_auto > 0 and id in ('".implode("','",Base::$aRequest['row_check'])."') group by id_own_auto");
				if (count($aAutoCnt) > 1) {
					$sMessage = "&aMessage[MT_ERROR]=Great 1 Check Auto Package";
					Form::RedirectAuto($sMessage);
					return;
				}
				
				$iCnt = Db::getOne("Select count(*) from user_account_log where custom_id in ('".implode("','",Base::$aRequest['row_check'])."')");
				if ($iCnt > 1) {
					$sMessage = "&aMessage[MT_ERROR]=finance operation exist not join";
					Form::RedirectAuto($sMessage);
					return;
				}
					
				
				$sManagerCommentOld = Db::GetOne("SELECT group_concat(manager_comment SEPARATOR '\n ')
					FROM `cart_package`	WHERE id in ('".implode("','",Base::$aRequest['row_check'])."')");
				$sCustomerCommentOld = Db::GetOne("SELECT group_concat(customer_comment SEPARATOR '\n ')
					FROM `cart_package`	WHERE id in ('".implode("','",Base::$aRequest['row_check'])."')");
				
				$aTotalSum = Db::GetRow("Select max(id) as id, sum(price_total) as price_total, sum(price_delivery) as price_delivery ".
						" from cart_package where id in ('".implode("','",Base::$aRequest['row_check'])."')");
				// package
				$sNumbers = implode(",",Base::$aRequest['row_check']);
				$aOldPackage = Db::GetRow("Select * from cart_package where id=".$aTotalSum['id']);
				$sText = '';
				if ($sManagerCommentOld)
					$sText = $sManagerCommentOld."\n";
				$sText .= date("d-m-Y H:i:s")." ".Language::getMessage('split orders').": ".Auth::$aUser['login'].
				", ".Language::getMessage('numbers').': '.$sNumbers;
				Db::Execute("Update cart_package set price_total='".$aTotalSum['price_total']."', price_delivery='".$aTotalSum['price_delivery']."' ".
					",manager_comment='".$sText."',customer_comment='".$sCustomerCommentOld."' where id=".$aTotalSum['id']);
				Db::Execute("Delete from cart_package where id in ('".implode("','",Base::$aRequest['row_check'])."') and id !=".$aTotalSum['id']);
				// cart
				Db::Execute("Update cart set id_cart_package=".$aTotalSum['id']." where id_cart_package in ('".implode("','",Base::$aRequest['row_check'])."')");
				
				// update auto?
				if ($aAutoCnt != array()) {
					$aAuto = Db::GetRow("Select * from cart_package where id=".$aTotalSum['id']);
					if ($aAuto['id_own_auto'] != $aAutoCnt[0]['id_own_auto'])
						Db::Execute("Update cart_package set id_own_auto=".$aAutoCnt[0]['id_own_auto'].", is_need_check=1, is_checked_auto=0 where id=".$aTotalSum['id']);
				}				
				$sMessage="&aMessage[MT_NOTICE]=Join orders successfully";
				
				if (Base::GetConstant("manager:enable_order_notification_on_email","1")) {
					$aCustomer=Db::GetRow( Base::GetSql('Customer',array('id'=>$aCnt[0]['id_user'])) );
						
					$sUserCartSql=Base::GetSql("Part/Search",array(
							"type_"=>'order',
							"where"=> " and c.id_cart_package=".$aTotalSum['id']." and c.id_user='".$aCnt[0]['id_user']."'",
					));
					$aUserCart=Db::GetAll($sUserCartSql);
					$aUserCartId=array();
					foreach ($aUserCart as $iKey => $aValue) {
						$dPriceTotal+=Currency::PrintPrice($aValue['price'],null,2,"<none>")*$aValue['number'];
						$aUserCart[$iKey]['print_price'] = Currency::PrintPrice($aValue['price'],null,2,"<none>");
					}

					$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>$aTotalSum['id'])));
					$aSmartyTemplate=String::GetSmartyTemplate('manager_join_orders', array(
							'aCartPackage'=>$aCartPackage,
							'aCart'=>$aUserCart,
							'sListJoinOrders'=>$sNumbers,
					));
					// to user
					Mail::AddDelayed($aCustomer['email'],$aSmartyTemplate['name']." ".$aCartPackage['id'],
					$aSmartyTemplate['parsed_text'],'',"info",false);
					// to managers
					Mail::AddDelayed(Base::GetConstant('manager:email_recievers','info@mstarproject.com')
					,$aSmartyTemplate['name']." ".$aCartPackage['id'],
					$aSmartyTemplate['parsed_text'],'',"info",false);
				}
			}
		} 
		else $sMessage="&aMessage[MT_ERROR]=Check Package";
	
		Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetUserSelect(){
		if(Auth::$aUser['is_super_manager'])
			$sWhereManager = '';
		else
		 	$sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."'";
		 	
		$sWhereManager .= " and (uc.name like '%".Base::$aRequest['data']['term']."%' or 
		    u.login like '%".Base::$aRequest['data']['term']."%' or 
		    Replace(Replace(Replace(u.login,'(',''),')',''),'-','') like '%".Base::$aRequest['data']['term']."%' or 
		    uc.phone like '%".Base::$aRequest['data']['term']."%' )";
		$aUsersArray = Db::GetAll("select id as id, 
					concat(ifnull(uc.name,''),' ( ',u.login,' )', 
				IF(uc.phone is null or uc.phone='','',concat(' ".
		Language::getMessage('tel.')." ',uc.phone))) name
			from user as u
			inner join user_customer as uc on u.id=uc.id_user
			where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
			".$sWhereManager."
			order by uc.name");
		echo json_encode($aUsersArray);
		die();
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPopularProducts(){
	    
		if(Base::$aRequest['action']=="manager_popular_products_add"){
			$aData=String::FilterRequestData(Base::$aRequest['data']);
			if($aData['code'] && $aData['pref'] ) {
				$aPrice=Db::GetRow(Base::GetSql("Catalog/Price",array(
					'aItemCode'=>array($aData['pref']."_".$aData['code']),
					'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser)
				)));

				if($aPrice) {
					$aInsertData=array(
						'zzz_code'=>"ZZZ_".$aPrice['id'],
						'old_price'=>$aPrice['price'],
						'name'=>$aPrice['name_translate'],
						'bage'=>0,
						'visible'=>0
					);
					
					//select images
					$aArtIds=TecdocDb::GetImages(array(
					    'codes'=>$aData['code'],
					    'codesTD'=>$aData['code'],
					));
					if($aArtIds) $aInsertData['image']=$aArtIds[$aData['pref']."_".$aData['code']];
					
					Db::AutoExecute('popular_products',$aInsertData);
				}
			}
			
			Base::$oResponse->addScript("popupOpen('.js-popup-ok');");
		}
		
		$aField['cat_name']=array('title'=>'brand','type'=>'input','value'=>Base::$aRequest['search']['cat_name'],'name'=>'search[cat_name]');
		$aField['code']=array('title'=>'code','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]');
		
		$aData=array(
		    'sHeader'=>"method=get",
		    //'sContent'=>Base::$tpl->fetch('manager/form_popular_search.tpl'),
		    'aField'=>$aField,
		    'bType'=>'generate',
		    'sGenerateTpl'=>'form/index_search.tpl',
		    'sSubmitButton'=>'Search',
		    'sSubmitAction'=>'manager_popular_products',
		    'sReturnButton'=>'Clear',
		    'bIsPost'=>0,
		    'sWidth'=>'700px',
		    'sError'=>$sError,
		);
		$oForm=new Form($aData);
		 
		Base::$sText.=$oForm->getForm();
		// --- search ---
		if (Base::$aRequest['search']['cat_name']) $sWhere.=" and c.title = '".Base::$aRequest['search']['cat_name']."'";
		if (Base::$aRequest['search']['code']) $sWhere.=" and psl.code = '".Base::$aRequest['search']['code']."'";
		// --------------
		
		$sSql="select count(*) as popular, psl.pref, psl.code , c.title as brand
			from price_search_log as psl 
			inner join cat as c on c.pref=psl.pref and c.visible ".$sWhere."
			where 1=1
			group by psl.pref, psl.code 
			order by popular desc";
		
		$oTable=new Table();
		$oTable->iRowPerPage=100;
		$oTable->sSql=$sSql;
		
		$oTable->aColumn=array(
			'popular'=>array('sTitle'=>'Popular'),
			'brand'=>array('sTitle'=>'brand'),
			'code'=>array('sTitle'=>'code'),
			'action'=>array(),
		);
		$oTable->sDataTemplate='manager/row_popular_product.tpl';
		
		Base::$sText.=$oTable->getTable("popular products");
		
	}
	//-----------------------------------------------------------------------------------------------
	public function Provider()
	{
	    Base::$sText.=Base::$tpl->fetch('manager/link_calculation.tpl');
	    Resource::Get()->Add('/js/select_search.js');
	    
	    Base::$tpl->assign('aNameUser',array(0 =>'')+Db::GetAssoc("select u.login, up.name
		from user as u
		inner join user_provider as up on u.id=up.id_user
		where u.visible=1 /*and up.name is not null and trim(up.name)!=''*/
		order by up.name"));
	
	    $aData=array(
	        'sHeader'=>"method=get",
	        'sContent'=>Base::$tpl->fetch('manager/form_provider_search.tpl'),
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'manager_provider',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sError'=>$sError,
    		'sGenerateTpl'=>'form/index_search.tpl',
	    );
	    $oForm=new Form($aData);
	
	    Base::$sText.=$oForm->getForm();
	
	    // --- search ---
	    //if (Base::$aRequest['search']['login']) $sWhere.=" and u.login like '%".Base::$aRequest['search']['login']."%'";
	    if (Base::$aRequest['search_login']) {
	        $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
	        $sWhere.=" || up.name like '%".Base::$aRequest['search_login']."%')";
    	}
    	
    	if (Base::$aRequest['search']['name']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['name']."%'";
    	        // --------------
    	
        require_once(SERVER_PATH.'/class/core/Table.php');
        $oTable=new Table();
		$oTable->sSql="select up.*, ua.* ,u.*,
		pg.name as name_provider_group , m.pref, upg.id_group, m.amount as amount_group
		from user u
		inner join user_provider up on up.id_user=u.id
		inner join user_account ua on ua.id_user=u.id
		inner join provider_group pg on pg.id = up.id_provider_group
		left join user_provider_group upg on upg.id_user = u.id
		left join user_provider_group_main m on m.id = upg.id_group
	        where 1=1 and u.visible='1' ".$sWhere;
		$oTable->aColumn=array(
		'login'=>array('sTitle'=>'LoginProvider','sOrder'=>'u.login'),
		'pref'=>array('sTitle'=>'Prefix'),
		'name_provider_group'=>array('sTitle'=>'Group Name'),
		'amount'=>array('sTitle'=>'CustAmount','sWidth'=>'20%'),
	    'action'=>array(),
		);
		$oTable->aOrdered="order by up.name";
		$oTable->iRowPerPage=20;
		$oTable->sDataTemplate='manager/row_provider.tpl';
		$oTable->aCallback=array($this,'CallParseOrder');
	
		$iSumBalance=Db::GetOne("
		    select sum(ua.amount)
		    from user_account as ua
		    inner join user_provider as up on up.id_user=ua.id_user
		");
			Base::$tpl->assign('iSumBalance',$iSumBalance);
			Base::$sText.=$oTable->getTable("My providers");
	}
	//-----------------------------------------------------------------------------------------------
	public function RecalcBalanceCustomer($sUserLogin='',$dDelivery=0,$iOrderId=0) {
		$iIdUser = Db::getOne("Select id from user where login='".$sUserLogin."' and type_='customer'");
		if (!$iIdUser || !$iOrderId)
			return; 
		
		$aCartPackage = Db::getRow("Select * from cart_package where id=".$iOrderId." and id_user=".$iIdUser);
		if (!$aCartPackage || $aCartPackage['order_status']=="pending")
			return;
		$sPostDate = $aCartPackage['post_date'];  
		
    	$aPayDeliveryBalanceLog = Db::GetRow("Select * from user_account_log where operation='pay_delivery' and id_user=".$iIdUser);
    	$dDeliveryBalance=0;
    	if ($aPayDeliveryBalanceLog)
    		$dDeliveryBalance = abs($aPayDeliveryBalanceLog['amount']);
    	
    	Db::StartTrans();
    	
    	if ($dDelivery!=$dDeliveryBalance) {
    		Db::Execute("Delete from user_account_log where custom_id=".$iOrderId." and operation='pay_delivery' and id_user=".$iIdUser);
	    	if ($dDelivery > 0) {
	    		$sSection = $sData = '';$sOperation='pay_delivery';
	    		$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$sOperation."'");
	    		$bResult = Db::Execute("insert user_account_log(id_user,amount,description
				,custom_id,section,data
				,id_user_account_log_type_debit,id_user_account_log_type_credit
				,id_subconto1,id_subconto2,id_subconto3,operation,id_office,comment, post_date)
				values ('$iIdUser',-".abs($dDelivery).",'".mysql_real_escape_string($aOperation['name'])."'
				,'".mysql_real_escape_string($iOrderId)."','$sSection','".mysql_real_escape_string($sData)."'
				,'0','0','0','0','0','".$sOperation."',0,'','".$sPostDate."')");
	    		$iInsertId=Db::InsertId();
	    	}
    	}
    	// recalc
    	$this->RecalcBalance($iIdUser);
    	
    	Db::CompleteTrans();
	}
	//-----------------------------------------------------------------------------------------------
	public function RecalcBalance($iIdUser) {
		return;
		// recalc
		/*$aLogBalance = Db::getAll("Select * from user_account_log where id_user=".$iIdUser." order by post_date");
		$dAmount=0;
		foreach ($aLogBalance as $aValue) {
			$dAmount += $aValue['amount'];
			Db::Execute("Update user_account_log set account_amount='".$dAmount."' where id=".$aValue['id']);
		}
		Db::Execute("update user_account set amount=".$dAmount." where id_user='$iIdUser'");*/
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderSetOtpPrice()
	{
		if (Base::$aRequest['id']) {
			$sCid=str_replace(",",".",str_replace('+',' ',str_replace("\n",' ',urldecode(Base::$aRequest['cid']))));
				
			$aCart = Db::getRow("Select c.*
				from cart c
				where c.id=".Base::$aRequest['id']);
			if ($aCart && (!is_numeric($sCid) || $sCid<0))
				$sCid = $aCart['price'];
				
			if ($aCart && $sCid!=$aCart['price']) {
				// AOT-64 (1)
				$iDiscount = Db::getOne("Select group_discount from customer_group where name='Оптовый 5'");
				$a=Db::GetRow(Base::GetSql('Catalog/Price',array(
					'is_not_check_item_code' => 1,
					'not_change_recalc' => 1,
					'where'=>" and p.id='".str_replace("ZZZ_", '', $aCart['zzz_code'])."' ",
					'customer_discount'=>$iDiscount
				)));
				if (!Auth::$aUser['is_super_manager'] && $a['price']>$sCid)
					$sCid = $aCart['price'];
				else {
					$iId_GeneralCurrencyCode = Db::getOne("Select id from currency where id=1");
					$sOrderStatus = 'change_price';
					$aCartManager=Db::GetRow(Base::GetSql('Manager',array(
							'id'=>$aCart['id_manager'],
					)));
					$aCartCustomer=Db::GetRow(Base::GetSql('Customer',array(
							'id'=>$aCart['id_user'],
					)));
					
					$aCart['price'] = Currency::PrintPrice($aCart['price'],$iId_GeneralCurrencyCode,2,"<none>");
					
					$sPreviousValue=$aCart['price'];
					$sNextValue=$sCid;
				
					$dAmount=$aCart['number']*($aCart['price']-$sCid);
					$sSql="update cart set price='".$sCid."' where id='{$aCart['id']}'";
					DB::Execute("update cart set price_before_change='".$sPreviousValue."' where price_before_change=0 and id='".$aCart['id']."'");
				
					$aCart['order_status']=$sOrderStatus;
					$aCart['comment']=Language::getMessage('change_price_comment price_difference').':'
							.($sCid-$aCart['price']);
					if ($dAmount) {
						// check exist work status order
						$iWorkPayAlready = Db::getOne("Select id from user_account_log where custom_id=".$aCart['id_cart_package']." and operation='pending_work'");
						if ($iWorkPayAlready)
							Finance::Deposit($aCart['id_user'],$dAmount,Language::getMessage($sOrderStatus).' '.$aCart['id']
							." : $sPreviousValue => $sNextValue",$aCart['id_cart_package']
							,'internal','cart','',6);
					}
					Base::$db->Execute($sSql);
					
					Manager::SetPriceTotalCartPackage($aCart);
					
					$aCart['change_date'] = date('Y-m-d H:i:s');
					$sSubject = 'Price of part in order is changed';
					$aCart['price'] = $sCid;
					$aCart['price_before_change'] = ($aCart['price_before_change']>0 ? $aCart['price_before_change'] : $sPreviousValue);
					$sCode='change_price';
					$aCart['date']=DateFormat::getDateTime(time());
					Message::CreateDelayedNotification($aCart['id_user'], $sCode
						,array('aCart'=>$aCart, 'info' => $aCartCustomer, 'aManager' => $aCartManager),true,$aCart['id']);
					$aChangeResult=array(
						'bResult'=>true,
						'sMessage'=>Language::getMessage('Changed ok. But notification not created'),
						'sPreviousNext'=>" $sPreviousValue => $sNextValue",
						'bIdenticalValues'=>($sPreviousValue==$sNextValue ? true : false)
					);
					$sCustomValue=$aChangeResult['sPreviousNext'];
					$sComment=mysql_escape_string(Language::getMessage($sOrderStatus).': '.$sCustomValue.' '.$sComment);					
					$sCommentSqlUpdate=" , manager_comment= concat(manager_comment,'".$sComment."',' ; ') ";
					Base::$db->Execute("update cart set id_user=id_user	".$sCommentSqlUpdate." where id='".$aCart['id']."'");
					Base::$db->Execute("insert into cart_log (id_cart,post,order_status,comment,id_user_manager)
						values ('".$aCart['id']."',UNIX_TIMESTAMP(),'$sOrderStatus','$sComment',".Auth::$aUser["id"].")");
					
					$sTotal_otp=$aCart['number'].'/'.Currency::PrintSymbol(round($aCart['price']*$aCart['number'],2));
				}
			}
			Base::$oResponse->addScript("$('#div_edit_otp_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_save_otp_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_edit_otp_".Base::$aRequest['id']."').show();");
			Base::$oResponse->addScript("$('#div_view_otp_".Base::$aRequest['id']."').html('".Currency::PrintSymbol($sCid)."');");
			if ($sTotal_otp)
				Base::$oResponse->addScript("$('#total_otp_".Base::$aRequest['id']."').html('".$sTotal_otp."');");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function OrderSetCommentHand()
	{
		if (Base::$aRequest['id']) {
			$sCid=mb_substr(str_replace('+',' ',str_replace("\n",' ',urldecode(Base::$aRequest['cid']))),0,255,'UTF-8');
			$aCart = Db::getRow("Select c.*
				from cart c
				where c.id=".Base::$aRequest['id']);
			if ($aCart) {
				$sSql="update cart set comment_hand='".mysql_real_escape_string($sCid)."' where id='{$aCart['id']}'";
				Base::$db->Execute($sSql);
			}
			Base::$oResponse->addScript("$('#div_edit_ch_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_save_ch_".Base::$aRequest['id']."').hide();");
			Base::$oResponse->addScript("$('#img_edit_ch_".Base::$aRequest['id']."').show();");
			Base::$oResponse->addScript("$('#div_view_ch_".Base::$aRequest['id']."').html('".$sCid."');");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProvider() {
		if (!Base::$aRequest['id_user'])
			Base::Redirect("/pages/manager_provider");
		
		$aProvider=Db::GetRow(Base::GetSql('Provider',array(
			'id'=>Base::$aRequest['id_user'],
		)));
		if (!$aProvider)
			Base::Redirect("/pages/manager_provider");
		
		Base::$oContent->AddCrumb(Language::GetMessage('Providers'),'/pages/manager_provider');
		Base::$oContent->AddCrumb(Language::GetMessage('Set provider group'),'');
		
		Base::$tpl->assign('idProvider',$aProvider['id']);
		Base::$tpl->assign('aProviderInfo',$aProvider);
		$sPref = '';
		//Debug::PrintPre($aProvider);
		$aGroup = Db::getRow("Select upg.id_group, m.pref
			from user_provider_group upg
			inner join user_provider_group_main m on m.id = upg.id_group
			where upg.id_user=".$aProvider['id']);
		if ($aGroup) {
			Base::$tpl->assign('sPref',$aGroup['pref']);
			Base::$tpl->assign('iIdGroup',$aGroup['id_group']);
			$aProviderGroup = Db::getAll("Select upg.*, u.login, up.name
			from user_provider_group upg
			inner join user u on u.id = upg.id_user
			inner join user_provider up on up.id_user = upg.id_user
			inner join user_provider_group_main m on m.id = upg.id_group
			where upg.id_group=".$aGroup['id_group']);
			
			Base::$tpl->assign('aProviderGroup',$aProviderGroup);
		}
		else 
			Base::$tpl->assign('aProviderGroup',array($aProvider['id'] => array(
				'name' => $aProvider['name'],
				'login' => $aProvider['login'],
				'is_main' => 0,
				'pref' => '',
			)));
		//Base::$tpl->assign('sPef',$sPref);
		
		$sWhere = " and id_user!= ".$aProvider['id'];
		$aProviderGroupAll = Db::getAssoc("Select upg.id_user as key_, upg.id_user
			from user_provider_group upg
			inner join user u on u.id = upg.id_user
			inner join user_provider up on up.id_user = u.id");
		if ($aProviderGroupAll)
			$sWhere .= " and id_user not in (".implode(",",array_keys($aProviderGroupAll)).")";
			
		$sSql=Base::GetSql('UserProvider',array(
			'where' => $sWhere." and u.visible=1"));
		Base::$tpl->assign('aProvider',$a=Base::$db->getAll($sSql));
		//Debug::PrintPre($sSql);
		Base::$sText = Base::$tpl->fetch('manager/form_group_provider.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProviderSet() {
		if (Base::$aRequest['id'] && Base::$aRequest['ids']) {
			
			$aAll = array(Base::$aRequest['id'] => Base::$aRequest['id']);
			 
			$aMass = explode(",",Base::$aRequest['ids']);
			if ($aMass)
				foreach ($aMass as $sId)
					if ($sId) 
						$aAll[$sId] = $sId;

			if (!Base::$aRequest['id_group']) {
				Db::Execute("Insert into user_provider_group_main (pref) VALUES ('')");
				$iIdGroup = Db::InsertId();
			}
			else
				$iIdGroup = Base::$aRequest['id_group'];
					
			foreach ($aAll as $iId) {
				if ($sText!='')
					$sText .= ',';
			
				$sText .= '('.$iIdGroup.",".$iId.")";
			}
			if ($sText) {
				Db::Execute("Insert into user_provider_group 
					(id_group, id_user) VALUES ".$sText.
					" on duplicate key update id_user=values(id_user)");
			}
			// recalc balance group
			$dBalanceGroup = Finance::DebtAmountGroup($iIdGroup);
			Db::Execute("update user_provider_group_main set amount=".$dBalanceGroup." where id='".$iIdGroup."'");
		}
		Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProviderUnSet() {
		if (Base::$aRequest['id'] && Base::$aRequest['ids']) {
			$aProvider=Db::GetRow(Base::GetSql('Provider',array(
				'id'=>Base::$aRequest['id'],
			)));
			if ($aProvider) {
				if (Base::$aRequest['ids']) {
					$aIds = array();
					$aMass = explode(",",Base::$aRequest['ids']);
					if ($aMass)
					foreach ($aMass as $sId)
						if ($sId) 
							$aIds[$sId] = $sId;
					
					if ($aIds)
						Db::Execute("delete from user_provider_group 
							where id_user in (".implode(',',$aIds).")");
				}
			}
		}
		Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProviderSetMain() {
		if (Base::$aRequest['id_main']) {
			$aData = Db::getRow("Select * from user_provider_group where id_user=".Base::$aRequest['id_main']);
			if ($aData) {
				Db::Execute("Update user_provider_group set is_main=0 where id_group=".$aData['id_group']);
				Db::Execute("Update user_provider_group set is_main=1 where id_user=".$aData['id_user']);
			}
		}
		Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProviderSetPref() {
		if (Base::$aRequest['id']) {
			$aData = Db::getRow("Select * from user_provider_group_main where id=".Base::$aRequest['id']);
			if ($aData) {
				Db::Execute("Update user_provider_group_main set pref='".mysql_escape_string(Base::$aRequest['cid'])."' where id=".$aData['id']);
			}
		}
		Base::$oResponse->addScript("location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function GroupProviderDelGroup() {
		if (Base::$aRequest['id']) {
			$aData = Db::getRow("Select * from user_provider_group_main where id=".Base::$aRequest['id']);
			if ($aData) {
				Db::Execute("delete from user_provider_group_main where id='".$aData['id']."'");
				Db::Execute("delete from user_provider_group where id_group='".$aData['id']."'");
			}
		}
		Base::$oResponse->addScript("location.href='/pages/manager_provider';");
	}
	//-----------------------------------------------------------------------------------------------
	public function RecalcBalanceProvider($iIdUser,$iCartId=0,$dAmount=0) {
		// search and update log
		if ($iCartId!=0) {
			$aCart = Db::getRow("Select * from cart where id=".$iCartId." and id_provider=".$iIdUser);
			if ($aCart) {
				$aLog = Db::getAll("Select * from user_account_log where id_cart=".$iCartId." and id_user=".$iIdUser." and operation='pay_provider'");
				if ($aLog) {
					foreach ($aLog as $aValue) {
						$dAmountItem = $dAmount * $aCart['number'];
						$dAmountItem = abs($dAmountItem);
						// pay_provider - Поступление детали на склад (-)
						// pay_provider_rko, pay_provider_rko_prepay - Оплата поставщику РКО (+)
						// pay_provider_bv, pay_provider_bv_prepay - Оплата поставщику БВ (+)
						if ($aValue['operation']) {
							$aOperation = Db::GetRow("Select * from user_account_type_operation where code='".$aValue['operation']."'");
							if ($aOperation['formula_balance'] == '-')
								$dAmountItem = -$dAmountItem;
						}else {
							//$aValue['data']=='return_provider' =>  ПКО (+)
						}
						Db::Execute("Update user_account_log set amount='".$dAmountItem."' where id=".$aValue['id']);
					}
					// recalc balance single
					$dAmountBalance = Db::GetOne("Select amount from user_account ua
						inner join user u on u.id = ua.id_user and u.type_='provider' 
						and u.id=".$iIdUser);
						
					$aLog = Db::getAll("Select l.* from user_account_log l
						left join cart_package cp on cp.id = l.custom_id
						where (cp.id is not Null or l.data='debt_provider' or l.data='prepay_provider') and
						l.id_user = ".$iIdUser." order by l.post_date,l.id");
					$iFirst = 0;
					$dBalance = 0;
					if ($aLog) {
						foreach ($aLog as $aLogItem) {
							if ($aLogItem['amount']==0)
								continue;
							if (!$iFirst) {
								$dBalance = $aLogItem['amount'];
								$iFirst = 1;
								if ($aLogItem['account_amount']!=$dBalance)
									Db::Execute("Update user_account_log set account_amount=".$dBalance." where id=".$aLogItem['id']);
								continue;
							}
							$dBalance += $aLogItem['amount'];
							// correct log
							if (abs($dBalance - $aLogItem['account_amount']) > 0.00001) {
								Db::Execute("Update user_account_log set account_amount=".$dBalance." where id=".$aLogItem['id']);
							}
						}
					}
					// correct balance
					if (abs($dAmountBalance - $dBalance) > 0.00001) {
						Db::Execute("Update user_account set amount=".$dBalance." where id_user = ".$iIdUser);
					}
				}
			}
		}
		// recalc balance group
		// if user provider and exist group AOT-41
		$aGroup = Db::getRow("Select * from user_provider_group where id_user=".$iIdUser);
		if ($aGroup) {
			$dBalanceGroup = Finance::DebtAmountGroup($aGroup['id_group']);
			Db::Execute("update user_provider_group_main set amount=".$dBalanceGroup." where id='".$aGroup['id_group']."'");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SeparateCart() {
	    if(Base::$aRequest['id_cart']) {
	        $sReturn= trim(strip_tags(Base::$aRequest['return'])) ? trim(strip_tags(Base::$aRequest['return'])) : 'manager_package';
	        $sReturn = str_replace('action=','',$sReturn);
    	    
    	    $aCart=Db::GetRow("select * from cart where id='".Base::$aRequest['id_cart']."' ");
	        
    	    if(Base::$aRequest['is_post']) {
    	        if(Base::$aRequest['number']) {
    	            if(Base::$aRequest['number'] >= $aCart['number']) {
    	                Base::$aRequest['number']=($aCart['number']-1);
    	            }
    	            
    	            $iNumNew=$aCart['number']-Base::$aRequest['number'];
    	            $iLastNumber=Base::$aRequest['number'];
    	            
    	            Db::Execute("update cart set number='".$iNumNew."' where id='".$aCart['id']."' ");
    	            unset($aCart['id']);
    	            $aCart['number']=$iLastNumber;
    	            Db::AutoExecute('cart', $aCart);
    	            
    	            Base::Redirect("/?action=".$sReturn);
    	        } else {
    	            Base::$sText.="error";
    	        }
    	    }

    	    $aField['num']=array('title'=>'number','type'=>'input','value'=>$aCart['number'],'name'=>'number');
    	    $aField['id_cart']=array('title'=>'id_cart','type'=>'hidden','value'=>$aCart['id'],'name'=>'id_cart');
    	    $aField['return']=array('title'=>'return','type'=>'hidden','value'=>$sReturn,'name'=>'return');

    	    $aData=array(
//     	        'sHeader'=>"method=post",
    	        'sTitle'=>"separate cart",
    	        //'sContent'=>Base::$tpl->fetch('manager/profile.tpl'),
    	        'aField'=>$aField,
    	        'bType'=>'generate',
    	        'sSubmitButton'=>'Apply',
    	        'sSubmitAction'=>'manager_separate_cart',
    	        'sReturnButton'=>'return',
    	        'sReturnAction'=>$sReturn,
    	        'bIsPost'=>true,
    	        'sError'=>$sError,
    	    );
    	    $oForm=new Form($aData);
    	    Base::$sText.=$oForm->getForm();
    	    
	    } else {
	        Base::$sText.="error";
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckDeniedChangeStatus($iId=0,$sOrderStatus='') {
		if (!$iId || $sOrderStatus=='' || $sOrderStatus!='refused')
			return '';
		
		// check only package all refused, maybe cart item to?
		$iIdCartPackage = Db::getOne("Select id_cart_package from cart where id = ".$iId." and id_cart_package!=0");
		if (!$iIdCartPackage)
			return '';
		
		$aFinanceLog = Db::getRow("Select l.* from user_account_log l
			inner join user u on u.id = l.id_user
			where custom_id=".$iIdCartPackage." and u.type_='provider' order by l.id desc");
		if (!$aFinanceLog)
			return '';
		
		$iCntNotRefusedItem = Db::getOne("Select count(*) from cart where id_cart_package=".$iIdCartPackage." and order_status!='refused' and id!=".$iId);
		if (!$iCntNotRefusedItem) {
			$sDocument = Finance::getNameDocumentProvider($aFinanceLog);
			return Language::getMessage('not refused item, exist finance log provider')." документ:".$sDocument." сумма:".$aFinanceLog['amount']." дата:".Language::GetPostDateTime($aFinanceLog['post_date']);
		}
		return '';
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckDeniedDeletePackage($iIdCartPackage=0) {
		if (!$iIdCartPackage)
			return '';
		
		$aFinanceLog = Db::getRow("Select l.* from user_account_log l
			inner join user u on u.id = l.id_user
			where custom_id=".$iIdCartPackage." and u.type_='provider' order by l.id desc");
		if (!$aFinanceLog)
			return '';
	
		$sDocument = Finance::getNameDocumentProvider($aFinanceLog);
		return Language::getMessage('not delete package, exist finance log provider')." документ:".$sDocument." сумма:".$aFinanceLog['amount']." дата:".Language::GetPostDateTime($aFinanceLog['post_date']);
	}
}
?>