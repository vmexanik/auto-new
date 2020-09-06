<?
/**
 * @author Vladimir Fedorov
 * 
 */

class PaymentDeclarationManager extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Auth::NeedAuth('manager');
		Base::$bXajaxPresent = true;
		Base::$aData['template']['bWidthLimit']=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		if (!Auth::$aUser['id'])
			Base::Redirect('/');
			
		if (Auth::$aUser['type_'] != 'manager')
			Base::Redirect('/pages/payment_declaration');
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 and uc.name is not null and trim(uc.name)!=''
		/*and uc.id_manager='".Auth::$aUser['id_user']."'*/
		order by uc.name"));
		
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['id']=array('title'=>'#','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['number_declaration']=array('title'=>'Number declaration','type'=>'input','value'=>Base::$aRequest['search']['number_declaration'],'name'=>'search[number_declaration]');
		$aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aData=array(
		    'sHeader'=>"method=get",
		    //'sContent'=>Base::$tpl->fetch('payment_declaration/form_search_payment_declaration_manager.tpl'),
		    'aField'=>$aField,
		    'bType'=>'generate',
		    'sGenerateTpl'=>'form/index_search.tpl',
		    'sSubmitButton'=>'Search',
		    'sSubmitAction'=>'payment_declaration_manager',
		    'sReturnButton'=>'Clear',
		    'sReturnAction'=>'payment_declaration_manager',
		    'bIsPost'=>0,
		    'sWidth'=>'90%',
		    'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
		
		// --- search ---
		//if (Base::$aRequest['search']['user']) $sWhere.=" and u.login ='".Base::$aRequest['search']['user']."'";
		if (Base::$aRequest['search_login']) {
		    $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		if (Base::$aRequest['search']['id']) $sWhere.=" and pd.id ='".Base::$aRequest['search']['id']."'";
		if (Base::$aRequest['search']['id_cart_package']) $sWhere.=" and pd.id_cart_package ='".Base::$aRequest['search']['id_cart_package']."'";
		if (Base::$aRequest['search']['number_declaration']) $sWhere.=" and pd.number_declaration ='".Base::$aRequest['search']['number_declaration']."'";
		if (Base::$aRequest['search']['date']) {
		    $sWhere.=" and (pd.date_send >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
		    and pd.date_send <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
		}

		
		$oTable=new Table();
		$oTable->sSql="Select pd.*, pd.id as pd_id, u.login
		        from payment_declaration pd 
				left join user as u ON u.id = pd.id_user
				left join user_customer as uc ON uc.id_user = pd.id_user 
		    	 where 1=1".$sWhere;
		$oTable->aOrdered="order by date_send desc";
		$oTable->aColumn=array(
		    'id' => array('sTitle'=>'#'),
		    'id_cart_package'=>array('sTitle'=>'cartpackage #'),
			'date_send'=>array('sTitle'=>'Date send'),
			'user' =>array('sTitle' => 'Customer'), 
			'recipient'=>array('sTitle'=>'Recipient'),
			'carrier'=>array('sTitle'=>'Carrier'),
			'number_declaration'=>array('sTitle'=>'Number declaration'),
			'number_places'=>array('sTitle' => 'Number places' ),
			'action'=>array(),
		);
		$oTable->sDataTemplate='payment_declaration/row_payment_declaration_manager.tpl';
		$oTable->sButtonTemplate='payment_declaration/button_payment_declaration_manager.tpl';
		$oTable->bStepperVisible=true;
		$oTable->bHeaderVisible=true;
		$oTable->iRowPerPage=10;
		$oTable->bCountStepper=true;
		Base::$sText.=$oTable->getTable();
		
	}
	//-----------------------------------------------------------------------------------------------
	public function Add()
	{
		Base::$oContent->AddCrumb(Language::GetMessage('payment_declaration'),'');
		Base::$bXajaxPresent=true;
		$oCurrency = new Currency();
		$sError = '';
		$aData = array();

		if (Base::$aRequest['is_post']) {
			$aData = Base::$aRequest['data'];
			if (!Base::$aRequest['data']['date_send'] || Base::$aRequest['data']['date_send'] == '') {
				$iTime = time();
				$sTime = date("Y-m-d H:i:s", $iTime);
				$aData['date_send'] = date("d-m-Y H:i:s", $iTime);
			}
			elseif (($sTime=strtotime(Base::$aRequest['data']['date_send'])) === false) {
				$sError .= Language::GetMessage("Incorrect format date and time. Use format: d-m-Y H:i:s");
			}
			else
				$sTime = date("Y-m-d H:i:s",$sTime);
				
			if (!$aData['date_send'] && Base::$aRequest['data']['date_send'])
				$aData['date_send'] = Base::$aRequest['data']['date_send'];
				
			if (!Base::$aRequest['search_login'] || trim(Base::$aRequest['search_login']) == '') {
				$sError .= Language::GetMessage("Incorrect select user.");
			}
			else {
				$aUser = Db::GetRow("select * from user as u 
						inner join user_customer as uc ON uc.id_user = u.id  
						where login = '".Base::$aRequest['search_login']."'");
				if (!$aUser || $aUser['login'] != Base::$aRequest['search_login'])
					$sError .= Language::GetMessage("Incorrect select user.");
			}
			
			if (!Base::$aRequest['data']['number_declaration'] || trim(Base::$aRequest['data']['number_declaration']) == '') {
				if ($sError != '')
					$sError .= "<br>";
				$sError .= language::GetMessage("Incorrect value number declaration. Please fill this field.");
			}
			if (!Base::$aRequest['data']['number_places'] || (int)Base::$aRequest['data']['number_places'] == 0) {
				if ($sError != '')
					$sError .= "<br>";
				$sError .= language::GetMessage("Incorrect value number places. Please fill this field.");
			}
			if (!Base::$aRequest['data']['id_cart_package'] || (int)Base::$aRequest['data']['id_cart_package'] == 0) {
			    if ($sError != '')
			        $sError .= "<br>";
			    $sError .= language::GetMessage("Incorrect value id_cart_package. Please fill this field.");
			}
				
			if ($sError == '') {
				if (!isset(Base::$aRequest['id'])) {
					$sQuery = "Insert into payment_declaration (id_user, date_send, recipient, carrier, number_declaration, number_places, id_cart_package) VALUES
							(".$aUser['id'].",'".$sTime."','".strip_tags(Base::$aRequest['data']['recipient'])."',
							 '".strip_tags(Base::$aRequest['data']['carrier'])."','".
								strip_tags(Base::$aRequest['data']['number_declaration'])."','".
								strip_tags(Base::$aRequest['data']['number_places'])."','".
					            strip_tags(Base::$aRequest['data']['id_cart_package'])."')";
				}
				else {
					$sQuery = "Update payment_declaration set date_send = '".$sTime."', id_user = ".$aUser['id'].", recipient = '".
								strip_tags(Base::$aRequest['data']['recipient'])."', carrier = '".
								strip_tags(Base::$aRequest['data']['carrier'])."', number_declaration = '".
								strip_tags(Base::$aRequest['data']['number_declaration'])."', number_places = '".
						      	strip_tags(Base::$aRequest['data']['number_places'])."', id_cart_package ='".
								strip_tags(Base::$aRequest['data']['id_cart_package'])."' where id = ".Base::$aRequest['id'];
								
				}
				$sMessage="Declaration created";
				$sSubject = Language::GetMessage('created new declaration');
				Base::$db->Execute($sQuery);
	
				$aData['aUser'] = Auth::$aUser;
				$aData['payment_declaration'] = array(
						'date' => $sTime,
						'recipient' => Base::$aRequest['data']['recipient'],
						'carrier' => Base::$aRequest['data']['carrier'],
						'number_declaration' => Base::$aRequest['data']['number_declaration'],
						'number_places' => Base::$aRequest['data']['number_places'],
				        'id_cart_package'=>Base::$aRequest['data']['id_cart_package'],
				);
		
				Message::CreateDelayedNotification($aUser['id_user'],'create_new_payment_declaration'
					,$aData,true);

				
				Base::Redirect("/pages/payment_declaration/?aMessage[MT_NOTICE]=".$sMessage);
			}
		}

		$sButtonSubmit = 'Add';
		if (Base::$aRequest['id']) {
			$aInfo = Db::GetRow("Select pd.*,u.login from payment_declaration pd  
					inner join user u on u.id = pd.id_user 
					where pd.id =".Base::$aRequest['id']);
			if ($aInfo['id']) {
				$aData = $aInfo;
				$sButtonSubmit = 'Edit';
			}
		}
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 and uc.name is not null and trim(uc.name)!=''
		/*and uc.id_manager='".Auth::$aUser['id_user']."'*/
		order by uc.name"));
		
		Base::$tpl->assign('aData',$aData);
		
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['is_post']=array('type'=>'hidden','name'=>'is_post','value'=>'1');
		$aField['date_send']=array('title'=>'Date and time send','type'=>'input','value'=>Base::$aRequest['data']['date_send']?Base::$aRequest['data']['date_send']:$aData['date_send'],'name'=>'data[date_send]');
		$aField['if_date_empty_text']=array('type'=>'text','value'=>Language::GetText('If empty - get current date and time. Use format: d-m-Y H:i:s'));
		$aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['recipient']=array('title'=>'Recipient','type'=>'input','value'=>Base::$aRequest['data']['recipient']?Base::$aRequest['data']['recipient']:$aData['recipient'],'name'=>'data[recipient]','szir'=>1);
		$aField['carrier']=array('title'=>'Carrier','type'=>'input','value'=>Base::$aRequest['data']['carrier']?Base::$aRequest['data']['carrier']:$aData['carrier'],'name'=>'data[carrier]','szir'=>1);
		$aField['number_declaration']=array('title'=>'Number declaration','type'=>'input','value'=>$aData['number_declaration'],'name'=>'data[number_declaration]','szir'=>1);
		$aField['number_places']=array('title'=>'Number places','type'=>'input','value'=>$aData['number_places'],'name'=>'data[number_places]','szir'=>1);
		$aField['id_cart_package']=array('title'=>'Cart package','type'=>'input','value'=>Base::$aRequest['data']['id_cart_package']?Base::$aRequest['data']['id_cart_package']:$aData['id_cart_package'],'name'=>'data[id_cart_package]','szir'=>1);
		
		if (!isset(Base::$aRequest['id'])) { 
		    $aData=array(
				'sHeader'=>"method=post",
				'sTitle'=>"Create declaration",
				//'sContent'=>Base::$tpl->fetch('payment_declaration/form_add_payment_declaration_manager.tpl'),
		        'aField'=>$aField,
		        'bType'=>'generate',
				'sSubmitButton'=>$sButtonSubmit,
				'sSubmitAction'=>'payment_declaration_manager_add',
				'sErrorNT'=>$sError,
				'sReturnButton'=>'<< Return',
				'sReturnAction'=>'payment_declaration_manager',
				'sReturnButtonClass' => '',
				'sSubmitButtonClass' => '',
		);
		}
		else {
		    $aData=array(
		        'sHeader'=>"method=post",
		        'sTitle'=>"declaration edit",
		        //'sContent'=>Base::$tpl->fetch('payment_declaration/form_add_payment_declaration_manager.tpl'),
		        'aField'=>$aField,
		        'bType'=>'generate',
		        'sSubmitButton'=>$sButtonSubmit,
		        'sSubmitAction'=>'payment_declaration_manager_edit',
		        'sErrorNT'=>$sError,
		        'sReturnButton'=>'<< Return',
		        'sReturnAction'=>'payment_declaration_manager',
		        'sReturnButtonClass' => '',
		        'sSubmitButtonClass' => '',
		    );
		}
		
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function Delete()
	{
		if (Auth::$aUser['type_'] != 'manager') {
			$sUrl = "/pages/payment_declaration";
			Base::Redirect($sUrl);
		}
		
		if (!Base::$aRequest['id'])
			$sMessage = 'Not found payment declaration item for delete';
		else {
			$aInfo = Db::GetRow("Select * from payment_declaration where id =".Base::$aRequest['id']);
			if (!$aInfo['id'])
				$sMessage = 'Not found payment declaration item for delete';
			else {
			 	$aUser = Db::GetRow("select * from user u 
							inner join user_customer uc ON uc.id_user = u.id
							where id = '".$aInfo['id_user']."'");
			
				Db::Execute("Delete from payment_declaration where id =".Base::$aRequest['id']);
	
				$aData['aUser'] = Auth::$aUser;
				$aData['payment_declaration'] = array(
						'date' => date("d-m-Y H:i:s",strtotime($aInfo['date_send'])),
						'recipient' => $aInfo['recipient'],
						'carrier' => $aInfo['carrier'],
						'number_declaration' => $aInfo['number_declaration'],
						'number_places' => $aInfo['number_places'],
				        'id_cart_package'=>$aInfo['id_cart_package'],
				);
				
				Message::CreateDelayedNotification($aUser['id_user'],'delete_new_payment_declaration'
						,$aData,true);

				$sMessage = 'Declaration item delete';
			}
		}
		$sUrl = "/pages/payment_declaration/?aMessage[MT_NOTICE]=".$sMessage;
		Base::Redirect($sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function SelectUser()
	{
		$aResult=array();
		if (Base::$aRequest['term']) {
			$aUsers = Db::GetAll("Select * from user u 
					inner join user_customer uc ON uc.id_user = u.id   
					where login like '%".Base::$aRequest['term']."%'");
			foreach($aUsers as $aValue)
				$aResult[] = array('label' => $aValue['login'] . ($aValue['name'] != '' ? ' - '. $aValue['name'] : ''), 'value' => $aValue['login'], 'id'=>$aValue['id']);	
		}
		echo json_encode($aResult);
		exit();
	}
}
?>