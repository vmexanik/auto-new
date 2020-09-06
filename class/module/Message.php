<?
/**
 * @author Mikhail Strovoyt
 *
 * @version 4.5.1
 * - added:AT-170 is_starred functionality
 */

class Message extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Base::$aData['template']['bWidthLimit']=true;
		Base::$aTopPageTemplate=array('panel/tab_'.Auth::$aUser['type_'].'.tpl'=>'message');
		Base::$bXajaxPresent=true;

		Resource::Get()->Add('/js/functions.js');
// 		Resource::Get()->Add('/libp/popcalendar/popcalendar.js');
	}
	//-----------------------------------------------------------------------------------------------
	function Prepare()
	{
		Auth::NeedAuth();
		if (!$_SESSION[message] || !$_SESSION[message][current_folder_id]) {
			$_SESSION[message][current_folder_id]="1";
			$_SESSION[message][current_folder_name]="Inbox";
			$_SESSION[message][current_step]="1";
			$_SESSION[message][order_field]="timestamp";
			$_SESSION[message][order_way]="desc";
		}
		Base::$aRequest[text]=substr(Base::$aRequest[text],0,1024);
		Base::$aRequest[to]=substr(Base::$aRequest[to],0,50);
		Base::$aRequest[subject]=substr(Base::$aRequest[subject],0,50);
		$aMessageNumber['inbox']=$this->MessageNumber(Auth::$aUser['id'],1);
		$aMessageNumber['outbox']=$this->MessageNumber(Auth::$aUser['id'],2);
		$aMessageNumber['draft']=$this->MessageNumber(Auth::$aUser['id'],3);
		$aMessageNumber['deleted']=$this->MessageNumber(Auth::$aUser['id'],4);
		Base::$tpl->assign('aMessageNumber',$aMessageNumber);
	}
	//-----------------------------------------------------------------------------------------------
	function MoveToFolder()
	{
		if (Base::$aRequest[id_message]=="") {
			foreach(Base::$aRequest['row_check'] as $value) $this->MoveMessage($value,Base::$aRequest[move_to_folder]);
		}
		else $this->MoveMessage(Base::$aRequest[id_message],Base::$aRequest[move_to_folder]);
		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Reply()
	{
		Resource::Get()->Add('/js/message_attachment.js',1);
		
		if (Base::$aRequest[id_message]) {
			$this->Prepare();

			Base::$tpl->assign("user_id",Auth::$aUser[id]);

			$message = Base::$db->getRow("SELECT * FROM message where id='".Base::$aRequest[id_message]."'".Auth::$sWhere);

			$aMessage['to']= $message[from];
			$aMessage['subject']= "RE:".$message[subject];
			$aMessage['from']= Auth::$aUser[login];
			Base::$tpl->assign('aMessage',$aMessage);

			Base::$tpl->assign("text","\n\n");
			Base::$tpl->assign("post_date", DateFormat::getDateTime($message[timestamp]));

			Base::$tpl->assign('to_input',1);
			Base::$tpl->assign('send_button',1);
			Base::$tpl->assign('draft_button',1);
			Base::$tpl->assign('discard_button',1);
			Base::$tpl->assign('subject_input',1);
			Base::$tpl->assign('textarea_begin',1);
			Base::$tpl->assign('textarea_end',1);
			Base::$tpl->assign('textarea_reply',1);

			Base::$tpl->assign("reply_text","On ".DateFormat::getDateTime($message[timestamp])
			." \n$message[from] wrote : \n----------------------------\n$message[text]");

			Base::$tpl->assign('section', "Messages - Reply");
			
			//get attached files
			$aFiles=Db::GetAll("select * from message_attachment where id_message='".Base::$aRequest[id_message]."' ");
			if ($aFiles) Base::$tpl->assign("aFiles", $aFiles);
			
			Base::$tpl->assign('sMainSection',Base::$tpl->fetch('message/view.tpl'));

			Base::$sText.=Base::$tpl->fetch('message/index.tpl');
		}
		else {
			$this->Browse();
		}
	}
	//-----------------------------------------------------------------------------------------------
	function Forward()
	{
		Resource::Get()->Add('/js/message_attachment.js',1);
		
		if (Base::$aRequest[id_message] || Base::$aRequest[id]) {
			$this->Prepare();

			if (Base::$aRequest[id_message]=="") {
				Base::$aRequest[id_message]=array_shift(Base::$aRequest[id]);
			}
			Base::$tpl->assign("user_id",Auth::$aUser[id]);

			$message = Base::$db->getRow("SELECT * FROM message where
						id='".Base::$aRequest[id_message]."'".Auth::$sWhere);
			$aMessage['subject']= "Fwd:".$message[subject];
			$aMessage['from']= Auth::$aUser[login];
			Base::$tpl->assign('aMessage',$aMessage);
			Base::$tpl->assign("to",'');


			Base::$tpl->assign("text","---------- Forwarded message ----------\nDate: "
			.DateFormat::getDateTime($message[timestamp])
			."\nSubject: $message[subject]\nFrom: $message[from]\nTo: $message[to]"
			."\n--------------------------------------\n$message[text]");
			Base::$tpl->assign("post_date", DateFormat::getDateTime($message[timestamp]));

			Base::$tpl->assign('to_input',1);
			Base::$tpl->assign('send_button',1);
			Base::$tpl->assign('draft_button',1);
			Base::$tpl->assign('discard_button',1);
			Base::$tpl->assign('subject_input',1);
			Base::$tpl->assign('textarea_begin',1);
			Base::$tpl->assign('textarea_end',1);
			
			//get attached files
			$aFiles=Db::GetAll("select * from message_attachment where id_message='".Base::$aRequest[id_message]."' ");
			if ($aFiles) Base::$tpl->assign("aFiles", $aFiles);

			Base::$tpl->assign('section', "Messages - Forward Message");
			Base::$tpl->assign('sMainSection',Base::$tpl->fetch('message/view.tpl'));

			Base::$sText.=Base::$tpl->fetch('message/index.tpl');
		}
		else {
			$this->Browse();
		}
	}
	//-----------------------------------------------------------------------------------------------
	function Send()
	{
		if (Base::$aRequest['compose'])
			unset(Base::$aRequest["id"]);
		
		$aAttachFiles=$this->GetAttachFiles();
		
		if (Base::$aRequest["id"]) {
			//get attached files
			$aFiles=Db::GetAll("select file_name, file_link from message_attachment where id_message='".Base::$aRequest["id"]."' ");
			$aAttachFiles=array_merge($aAttachFiles,$aFiles);
		}

		if (Base::$aRequest[reply_text]!='') Base::$aRequest[reply_text]="\n\n".Base::$aRequest[reply_text];//adding delimiters
		$this->SendMessage(Base::$aRequest[to],Base::$aRequest[subject],Base::$aRequest[text].Base::$aRequest[reply_text]
		,Base::$aRequest[id_message], true, $aAttachFiles);

		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Draft()
	{
		if (Base::$aRequest[reply_text]!='') Base::$aRequest[reply_text]="\n\n".Base::$aRequest[reply_text];//adding delimiters

		$aAttachFiles=$this->GetAttachFiles();
		
		$this->CreateMessage(Base::$aRequest[to],Base::$aRequest[subject],3,1,Base::$aRequest[text].Base::$aRequest[reply_text],'','',$aAttachFiles);
		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Compose()
	{
		Resource::Get()->Add('/js/message_attachment.js',1);

		Base::$oContent->AddCrumb(Language::GetMessage('Messages'),'');

		$this->Prepare();

		Base::$tpl->assign('to_input',1);
		Base::$tpl->assign('subject_input',1);
		Base::$tpl->assign('send_button',1);
		Base::$tpl->assign('draft_button',1);
		Base::$tpl->assign('discard_button',1);
		Base::$tpl->assign('post_date',DateFormat::getDateTime(time()));

		$aMessage['from']=Auth::$aUser['login'];
		$aMessage['to']= Base::$aRequest[message_to];
		Base::$tpl->assign('aMessage',$aMessage);
		
		Base::$tpl->assign('compose',1);
		
		Base::$tpl->assign('user_id',Auth::$aUser['user_id']);
		Base::$tpl->assign('textarea_begin',1);
		Base::$tpl->assign('textarea_end',1);

		Base::$tpl->assign('section', "Messages - Compose Message");
		Base::$tpl->assign('sMainSection',Base::$tpl->fetch('message/view.tpl'));

		Base::$sText.=Base::$tpl->fetch('message/index.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	function Delete()
	{
		if (Base::$aRequest['row_check']) {
			foreach(Base::$aRequest['row_check'] as $value) $this->DeleteMessage($value);
		}
		else $this->DeleteMessage(Base::$aRequest['id']);

		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Clear()
	{
		//		Base::$db->Execute("delete from message where id_message_folder='4' ".Auth::$sWhere);
		//		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Preview()
	{
		Resource::Get()->Add('/js/message_attachment.js',1);
		
		$aMessage = Base::$db->getRow("SELECT * FROM message where
					id='".Base::$aRequest[id]."' ".Auth::$sWhere);
		if ($aMessage) {
			$this->Prepare();

			$aUserList=$this->getUserList(array("'".$aMessage['from']."'"));
			$aUser=$aUserList[0];
			Base::$tpl->assign("aUser",$aUser);
			Base::$tpl->assign("aMessage", $aMessage);
			Base::$tpl->assign("id_message", $aMessage['id']);
			Base::$tpl->assign("post_date", DateFormat::getDateTime($aMessage[timestamp]));
			
			//get attached files
			$aFiles=Db::GetAll("select * from message_attachment where id_message='".$aMessage['id']."' ");
			if ($aFiles) Base::$tpl->assign("aFiles", $aFiles);
			if (Base::$aRequest["draft"]) Base::$tpl->assign("bDraft", 1);

			if ($aMessage[id_message_folder]==3) {
				//Preview draft messages.
				Base::$tpl->assign('to_input',1);
				Base::$tpl->assign('subject_input',1);
				Base::$tpl->assign('send_button',1);
				Base::$tpl->assign('textarea_begin',1);
				Base::$tpl->assign('textarea_end',1);
				Base::$tpl->assign('text', $aMessage['text']);
			}
			else {
				Base::$tpl->assign('text', $aMessage['text']);
				Base::$tpl->assign('subject_text',1);
				Base::$tpl->assign('to_text',1);

				if ($aMessage[id_message_folder]==1) Base::$tpl->assign('reply_button',1);
			}

			Base::$tpl->assign('discard_button',1);
			Base::$tpl->assign('forward_button',1);

			Base::$tpl->assign('aMessage', $aMessage);

			Base::$tpl->assign('section', "Messages - View");
			Base::$tpl->assign('sMainSection',Base::$tpl->fetch('message/view.tpl'));

			Base::$sText.=Base::$tpl->fetch('message/index.tpl');

			$this->ReadMessage(Base::$aRequest[id]);
		}
		else {
			$this->Browse();
		}
	}
	//-----------------------------------------------------------------------------------------------
	function ChangeCurrentFolder()
	{
		if (Base::$aRequest[id_message_folder]!="") {
			$_SESSION[message][current_folder_id]=Base::$aRequest[id_message_folder];
			switch (Base::$aRequest[id_message_folder]) {
				case 2: $sTitle="Outbox"; break;
				case 3: $sTitle="Draft"; break;
				case 4: $sTitle="Deleted"; break;
				default: $sTitle="Inbox";
			}
			$_SESSION[message][current_folder_name]=$sTitle;
		}
		$this->Browse();
	}
	//-----------------------------------------------------------------------------------------------
	function Browse()
	{
		$this->Prepare();

		$aField['search_from']=array('title'=>'From','type'=>'input','value'=>Base::$aRequest['search_from'],'name'=>'search_from');
		$aField['search_to']=array('title'=>'To','type'=>'input','value'=>Base::$aRequest['search_to'],'name'=>'search_to');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('message/form_message_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'message',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'message',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$tpl->assign('sSearchForm',$oForm->getForm());

		// --- search ---
		if (Base::$aRequest['search_from']) $sWhere.=" and m.from ='".Base::$aRequest['search_from']."'";
		if (Base::$aRequest['search_to']) $sWhere.=" and m.to ='".Base::$aRequest['search_to']."'";
// 		if (Base::$aRequest['search']['date']) {
// 			$sWhere.=" and (m.timestamp>='".strtotime(Base::$aRequest['search']['date_from'])."'
// 				and m.timestamp<='".strtotime(Base::$aRequest['search']['date_to'],' 23:59:59')."')";
// 		}
		if (Base::$aRequest['search']['date']) {
    		$sWhere.=" and (m.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
    				and m.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."')";
		}

		$sWhere.=" and m.id_user='".Auth::$aUser['id_user']."'";
		if ($_SESSION['message']['is_starred']) {
			$sWhere.=" and m.is_starred='1'";
		}
		
		if ($_SESSION[message][current_folder_id]==3) Base::$tpl->assign('bDraft',1);
		// --------------

		$oTable=new Table();
		$oTable->iRowPerPage=20;
		$oTable->sSql=Base::GetSql('Message',array(
		'where'=> " and m.id_message_folder='".$_SESSION[message][current_folder_id]."' ".$sWhere,
		'join_user_from_to'=>1,
		));

		$oTable->aOrdered=" order by m.id desc ";
		$oTable->aColumn=array(
		'subject'=>array('sTitle'=>'Subject'),
		'from'=>array('sTitle'=>'From'),
		'to'=>array('sTitle'=>'To'),
		'post'=>array('sTitle'=>'Post Date'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='message/row_message.tpl';

		$oTable->aCallback=array($this,'CallParseMessage');
		$oTable->bFormAvailable=false;
		$oTable->bCheckAllVisible=true;
		$oTable->bCheckVisible=true;
        $oTable->sTemplateName='table/index2.tpl';
        $oTable->bHeaderVisible=false;

		$sMainSection=$oTable->getTable().Base::$tpl->fetch('message/browse_bottom.tpl');
		Base::$tpl->assign('sMainSection',$sMainSection);

		Base::$sText.=Base::$tpl->fetch('message/index.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseMessage(&$aItem)
	{
		if ($aItem) foreach($aItem as $key => $value) {
			$aItem[$key]['post_date']=DateFormat::getDateTime($value['timestamp']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	function MessageNumber($iIdUser,$iIdMessageFolder)
	{
		/**
		 * Need to implement bak__ archiving for such messages
		 */
		//$sWhere.=" and is_old='0'";
		return Base::$db->getOne("select count(*) from message where
			id_user='".$iIdUser."'
			and id_message_folder='".$iIdMessageFolder."'
			".$sWhere);
	}
	//---------------------------------------------------------------------------------------------------------
	function CreateMessage($sTo,$sSubject,$iIdMessageFolder,$iIsRead,$sText,$iIdUser='',$sUserFrom='',$aAttachFiles=array())
	{
		if (!$iIdUser) $iIdUser=Auth::$aUser['id'];

		if (!$iIdUser && $iIdMessageFolder=2) return;//don't create messages from unknown

		if (!$sUserFrom) $sUserFrom=Auth::$aUser['login'];

		Base::$db->Execute("insert into message (`from`, `to`, subject, timestamp, id_user, id_message_folder,is_read, text)
			values
	    	     ('$sUserFrom', '$sTo', '$sSubject', UNIX_TIMESTAMP(), '$iIdUser',
	    	     	'$iIdMessageFolder', '$iIsRead',  '".mysql_real_escape_string($sText)."')");
		$iMessageId=Base::$db->Insert_ID();
		
		//attach files
		foreach ($aAttachFiles as $aValue) {
			if (strlen($aValue['file_link'])>0){
				$aData=array(
					"id_message"=>$iMessageId,
					"file_name"=>$aValue['file_name'],
					"file_link"=>$aValue['file_link'],
				);
				Db::Autoexecute('message_attachment',$aData);
			}
		}
		
		return $iMessageId;
	}
	//---------------------------------------------------------------------------------------------------------
	function SendMessage($sTo,$sSubject,$sText,$iIdMessage='',$bSendEmail=true, $aAttachFiles=array())
	{
		$aWord=preg_split("/[\s,]+/",trim($sTo));
		if ($aWord) {
			foreach ($aWord as $key => $value) $aWord[$key]="'".$value."'";

			$aUser=Message::getUserList($aWord);
			
			if ($aUser) {
				$iSendTo = 0;
				foreach ($aUser as $key => $value) {
					//if ($value['login'] == $sTo)
					if ($value['login'] == Auth::$aUser['login'])
						$iSendTo = 1;
					
					Message::CreateMessage($value['login'],$sSubject,1,0,$sText,$value['id'],'',$aAttachFiles);
					//Send a copy of message to uesr's email
					if ($value[copy_message] && $bSendEmail) {
						require_once(SERVER_PATH.'/class/core/Mail.php');
						Mail::SendNow($value['email'],$sSubject,stripslashes($sText));
					}
				}
				// send only one
				if (!$iSendTo)
					Message::CreateMessage($sTo,$sSubject,2,1,$sText,'','',$aAttachFiles);
				
				Message::EraseMessage($iIdMessage);
			}
			else Base::Message(array('MF_ERROR'=>'Unknown users to send message'));
		}
		else Message::ShowError();
	}
	//---------------------------------------------------------------------------------------------------------
	function DeleteMessage($iId)
	{
		$aMessage=Base::$db->getRow("select * from message where id='$iId'");
		//		if ($aMessage[id_message_folder]==4)
		//		Base::$db->Execute("delete from message where id='$iId' and id_user='".Auth::$aUser[id]."'");
		//		else
		Base::$db->Execute("update message set id_message_folder='4' where id='$iId' and id_user='".Auth::$aUser[id]."'");
	}
	//---------------------------------------------------------------------------------------------------------
	function EraseMessage($iId)
	{
		if ($iId) Base::$db->Execute("delete from message where id='$iId' and id_user='".Auth::$aUser[id]."'");
	}
	//---------------------------------------------------------------------------------------------------------
	function MoveMessage($iId,$iIdMessageFolder)
	{
		if ($iId && $iIdMessageFolder)
		Base::$db->Execute("update message set id_message_folder='$iIdMessageFolder'
			where id='$iId' and id_user='".Auth::$aUser[id]."'");
	}
	//---------------------------------------------------------------------------------------------------------
	function getUserList($aLogin)
	{
		//$admin=Base::$db->getRow("select * from admins where login='$sLogin'");
		//if ($admin[id]!="") return $admin;
		$sWhere.=" or login in (".implode(",", $aLogin).")";
		return Base::$db->getAll("select * from user where 1!=1 ".$sWhere);
	}

	//---------------------------------------------------------------------------------------------------------
	function ReadMessage($iId)
	{
		if ($iId) Base::$db->Execute("update message set is_read='1' where id='$iId' and id_user='".Auth::$aUser[id]."'");
	}
	//---------------------------------------------------------------------------------------------------------
	//	function ShowError($sLogin='')
	//	{
	//		$xtpl->assign("login", $sLogin);
	//		//$xtpl->assign("ERR_MSG", "$error_text");
	//		$xtpl->parse("main.register_error.error");
	//		$xtpl->parse("main.register_error");
	//	}
	//---------------------------------------------------------------------------------------------------------
	function CreateNotification($sTo,$sCode,$sType='customer',$aUserData=array(),$sToId='',$iPriority=3)
	{
		if (is_numeric($sToId)) $sWhere.=" or u.id='$sToId'";

		if ($sType=='customer') {
			$aUser=Base::$db->getRow("select u.*, uc.*, cg.name as customer_group_name
				from user u
				inner join user_customer uc on u.id=uc.id_user
				inner join customer_group cg on cg.id=uc.id_customer_group
				inner join user_account ua on u.id=ua.id_user
				where (u.login='$sTo' $sWhere)
				group by u.id");
		}
		else $aUser=Base::$db->getRow("select u.*	from user u	where (u.login='$sTo' $sWhere)");
		if (!$aUser) return false;

		$aData=array('user'=>$aUser,'user_data'=>$aUserData);
		require_once(SERVER_PATH.'/class/system/Content.php');
		require_once(SERVER_PATH.'/class/core/String.php');
		$sText=String::GetSmartyTemplate($sCode,$aData);

		$sSubject=String::FirstNwords($sText,5)." [".$aUser['login']."]";
		//$sSubject=Base::$aConstant['notification_subject']['value'];
		Message::SendMessage($aUser['login'],$sSubject,$sText);
		//echo("<br>".$aUser['login'].$sSubject.$sText);
	}
	//---------------------------------------------------------------------------------------------------------
	/**
	 * New method for sending notifications with delay, priority and bulk queue
	 */
	function CreateDelayedNotification($iIdUser,$sCode,$aUserData=array(),$bSmartyTemplate=false,$iIdCart=0)
	{
		$aUser=Base::$db->getRow("select u.* from user as u	where u.id='$iIdUser'");
		if (!$aUser) return false;

		switch($aUser['type_']) {
			case 'customer':
			default: $aUser=Db::GetRow(Base::GetSql('Customer',array(
			'id'=>$aUser['id'],
			'join_language'=>1,
			)));
		}

		$aUserNotificationRefused=Base::$db->GetAssoc("select code as id,id_user as value from user_notification_refused
			 where id_user='$iIdUser'");
		if ($aUser['receive_notification'] && !in_array($sCode,array_keys($aUserNotificationRefused)) ) {
			if ($bSmartyTemplate) {

				if ($aUser['code_language'] && $aUser['code_language']!=Language::$sBaseLocale) {
					$sBeforeLocale=Language::$sLocale;
					Language::$sLocale=$aUser['code_language'];
					$bChagedLocale=true;
				}
				$aTemplate=String::GetSmartyTemplate($sCode,$aUserData);
				if ($bChagedLocale) {
					Language::$sLocale=$sBeforeLocale;
				}

				$sText=$aTemplate['parsed_text'];
				$sSubject=$aTemplate['name']." [".$aUser['login']."]";
			}
			else {
				$aData=array('user'=>$aUser,'user_data'=>$aUserData);
				$aTemplate=Base::$db->GetRow(Base::GetSql('Template',array('code'=>$sCode)));
				//$sText=Content::getTemplate($sCode,$aData);
				$aText=String::GetSmartyTemplate($sCode,$aUserData);
				$sText=$aText['parsed_text'];
				$sSubject=String::FirstNwords($sText,7)." [".$aUser['login']."]";
			}


			if ($aUser['notification_type']=='single') {
				Message::SendMessage($aUser['login'],$sSubject,$sText,'',false);
				if ($aUser['copy_message']) {
					Mail::AddDelayed($aUser['email'],$sSubject,$sText,'','',true,$aTemplate['priority']);
				}
			}
			else {
				$aUserNotification=array(
				'id_user'=>$aUser['id'],
				'subject'=>$sSubject,
				'description'=>$sText,
				'id_cart'=>$iIdCart,
				);
				Db::AutoExecute('user_notification',$aUserNotification);
			}
		}
	}
	//---------------------------------------------------------------------------------------------------------
	function SendBulkUserNotification($sAdditionalWhere='')
	{
		$aUserNotification=Db::GetAll(Base::GetSql('UserNotification',array(
		'is_sent'=>'0',
		'where'=>" and u.notification_hour like '%".ltrim(Date('H'),'0').",%' ".$sAdditionalWhere,
		'order'=>" order by un.id_user,un.post_date",
		)));
		$aIdUser=array();
		if ($aUserNotification) foreach ($aUserNotification as $value) {
			$aUserNotificationGrouped[$value['id_user']][]=$value;
			if (!in_array($value['id_user'],$aIdUser)) $aIdUser[]=$value['id_user'];
			$aIdUserNotification[]=$value['id'];
		}
		if ($aIdUser) {
			foreach ($aIdUser as $value) {
				//if ($aUserNotificationGrouped[$value]) foreach ($aUserNotificationGrouped[$value] as $aNotification)
				$sSubject=Language::GetMessage('bulk_messages').' - '.DateFormat::getDateTime(time());
				Base::$tpl->assign('aUserNotification',$aUserNotificationGrouped[$value]);
				$sText=Base::$tpl->fetch('message/user_notification_list.tpl');
				Base::$tpl->assign('sLetterUrl','http://'.SERVER_NAME.'/');
				$sLetterText=Base::$tpl->fetch('message/user_notification_list.tpl');

				Message::SendMessage($aUserNotificationGrouped[$value][0]['login'],$sSubject,$sText,'',false);
				if ($aUserNotificationGrouped[$value][0]['copy_message']) {
					if ($aUserNotificationGrouped[$value][0]['include_reference_excel']) {
						$sAttachmentCode=Message::CreateCustomerIdExcel($aUserNotificationGrouped[$value]);
					}
					Mail::AddDelayed($aUserNotificationGrouped[$value][0]['email'],$sSubject,$sLetterText,
					Base::GetConstant('mail:from','info@mstarproject.com'),Base::GetConstant('mail:from_name','Info'),
					true,2,$sAttachmentCode); /* add excel */
				}
			}

			//if (0)
			Db::Execute("update user_notification set is_sent='1' where id in (".implode(',',$aIdUserNotification).")");
		}
	}
	//---------------------------------------------------------------------------------------------------------
	function PreviewUserNotification()
	{
		Auth::NeedAuth();

		$aUserNotification=Base::$db->GetRow(Base::GetSql('UserNotification',array(
		'id'=>Base::$aRequest['id'],
		'id_user'=>Auth::$aUser['id'],
		)));
		Base::$tpl->assign('aUserNotification',$aUserNotification);
		Base::$sText.=Base::$tpl->fetch('message/preview_user_notification.tpl');
	}
	//---------------------------------------------------------------------------------------------------------
	/**
	 * Send Alert to user when he is logged onto site. Odnoklasniki analog
	 * Name - message header
	 * Description - message text (can be html)
	 * Url - link for demo// not implemented yet
	 * ReplyTo button or link to reply// not implemented yet
	 */
	function AddNote($iIdUser,$sName,$sDescription,$sUrl='',$sReplyTo='')
	{
		$aMessageNote=array(
		'id_user'=>$iIdUser,
		'name'=>$sName,
		'description'=>$sDescription,
		'url'=>$sUrl,
		'reply_to'=>$sReplyTo,
		'post'=>time(),
		);
		Base::$db->AutoExecute('message_note', $aMessageNote, 'INSERT');
	}
	//---------------------------------------------------------------------------------------------------------
	function CheckNote()
	{
		if (!Auth::$aUser['id']) return;
		return Base::$db->getRow(Base::GetSql('MessageNote',array('id_user'=>Auth::$aUser['id'],'is_closed'=>0)));
	}
	//---------------------------------------------------------------------------------------------------------
	function NoteClose()
	{
		if (!Auth::$aUser['id']) return;
		$aMessageNote=Base::$db->getRow(Base::GetSql('MessageNote',
		array(
		'id_user'=>Auth::$aUser['id']
		,'is_closed'=>0
		,'id'=>Base::$aRequest['id']
		)));
		Base::$oResponse->addAssign('message_note','innerHTML','');
		Base::$db->Execute("update message_note set is_closed='1' where id='".Base::$aRequest['id']."'");
		if ($aMessageNote['url']) {
			Base::$oResponse->addScript("location.href='".$aMessageNote['url']."'");
		}
	}
	//---------------------------------------------------------------------------------------------------------
	/**
	 * Create attachment excel file and puts code for delayed message
	 *
	 * @param string $sOwnerCode of created attachment in 'attachment' table after file creation
	 */
	function CreateCustomerIdExcel($aUserNotification)
	{
		if (!$aUserNotification) return;
		$aCartId=array();
		foreach ($aUserNotification as $aValue) {
			if ($aValue['id_cart'] && !in_array($aValue['id_cart'],$aCartId) ) $aCartId[]=$aValue['id_cart'];
		}
		if (!$aCartId) return;

		$oExcel = new Excel();
		$aHeader=array(
		'A'=>array("value"=>'ci_id_cart'),
		'B'=>array("value"=>'ci_id_cart_package'),
		'C'=>array("value"=>'ci_cat_name'),
		'D'=>array("value"=>'ci_code', 'autosize'=>true),
		'E'=>array("value"=>'ci_order_status', 'autosize'=>true),
		'F'=>array("value"=>'ci_name', 'autosize'=>true),
		'G'=>array("value"=>'ci_customer_id', 'autosize'=>true),
		'H'=>array("value"=>'ci_comment', 'autosize'=>true),
		'I'=>array("value"=>'ci_region_name'),
		'J'=>array("value"=>'ci_number'),
		'K'=>array("value"=>'ci_price'),
		'L'=>array("value"=>'ci_total'),
		'M'=>array("value"=>'ci_sign'),
		'N'=>array("value"=>'ci_current_weight'),
		'O'=>array("value"=>'ci_post_weight'),
		'P'=>array("value"=>'ci_weight_delivery_cost_post'),
		);

		$oExcel->SetHeaderValue($aHeader,1);
		$oExcel->SetAutoSize($aHeader);
		$oExcel->DuplicateStyleArray("A1:P1");

		$sSql=Base::GetSql("Part/Search",array(
		"where"=>" and c.id in (".implode(',',$aCartId).")",
		"order"=>" order by c.customer_id, pr.name, c.post_date",
		));
		$aCart=Db::GetAll($sSql);
		if ($aCart) {
			$i=$j=2;
			foreach ($aCart as $aValue) {
				$oExcel->setCellValue('A'.$i, $aValue['id']);
				$oExcel->setCellValue('B'.$i, $aValue['id_cart_package']);
				$oExcel->setCellValue('C'.$i, $aValue['cat_name']);
				$oExcel->setCellValue('D'.$i, String::UtfEncode(' '.$aValue['code']
				.($aValue['code_changed'] ? '/'.$aValue['code_changed']:'')));
				$oExcel->setCellValue('E'.$i, $aValue['order_status']);
				$oExcel->setCellValue('F'.$i, String::UtfEncode($aValue['russian_name']));
				$oExcel->setCellValue('G'.$i, String::UtfEncode($aValue['customer_id']));
				$oExcel->setCellValue('H'.$i, String::UtfEncode($aValue['manager_comment']));
				$oExcel->setCellValue('I'.$i, String::UtfEncode($aValue['pr_name'].' '.$aValue['prw_name']));
				$oExcel->setCellValue('J'.$i, $aValue['number']);
				$oExcel->setCellValue('K'.$i, $aValue['price']);
				$oExcel->setCellValue('L'.$i, $aValue['number']*$aValue['price']);
				$oExcel->setCellValue('M'.$i, $aValue['sign']);
				$oExcel->setCellValue('N'.$i, $aValue['cpt_weight']);
				$oExcel->setCellValue('O'.$i, $aValue['weight_post']);
				$oExcel->setCellValue('P'.$i, $aValue['weight_delivery_cost_post']);
				$i++;
			}
			$sFileName=DateFormat::GetFileDateTime(time()).'_'.$aCart[0]['login'].'_customer_id.xls';
			$sFullFileName='/imgbank/temp_upload/attachment/'.$sFileName;
			$oExcel->WriterExcel5(SERVER_PATH.$sFullFileName);

			$aAttachment=array(
			'owner_code'=>$sFileName,
			'attach_file'=>$sFullFileName,
			'is_temporary'=>1,
			);
			Db::AutoExecute('attachment',$aAttachment);

			return $sFileName;
		}

	}
	//---------------------------------------------------------------------------------------------------------
	function ChangeStarred()
	{
		$_SESSION['message']['is_starred']=Base::$aRequest['is_starred'];
		Base::$oResponse->AddScript("window.location.reload();");
	}
	//---------------------------------------------------------------------------------------------------------
	function ChangeStarredMessage()
	{
		if (Base::$aRequest['id_message']) {
			$aMessage=Db::GetRow(Base::GetSql('Message',array(
			'id'=>Base::$aRequest['id_message'],
			'where'=>" and m.id_user='".Auth::$aUser['id']."'",
			)));
			if ($aMessage) {
				$aMessage['is_starred']=!$aMessage['is_starred'];
				Db::AutoExecute('message',array('is_starred'=>$aMessage['is_starred']),'UPDATE'
				,"id='".Base::$aRequest['id_message']."'");

				Base::$tpl->assign('bXajaxRequest',true);
				Base::$tpl->assign('aData',$aMessage);
				Base::$oResponse->AddAssign($aMessage['id'].'_is_starred_span_id','innerHTML'
				,Base::$tpl->fetch("message/is_starred.tpl"));
			}

		}
	}
	//---------------------------------------------------------------------------------------------------------
	function GetAttachFiles() {
		//get files list
		$aAllFiles=$_FILES;
		$aAttachFiles=array();
		
		$sDate=date("Y/m");
		if (!file_exists(SERVER_PATH."/imgbank/Image/messages_attachment/".$sDate)) {
			if ( !file_exists(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y"))) {
				//year folder missing
				if (!file_exists(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y")."/".date("m"))) {
					$bCreateDir=mkdir(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y"));
					$bCreateDir=mkdir(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y")."/".date("m"));
				}
			} else {
				//year folder exists
				if (!file_exists(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y")."/".date("m"))) {
					$bCreateDir=mkdir(SERVER_PATH."/imgbank/Image/messages_attachment/".date("Y")."/".date("m"));
				}
			}
		}
		
		foreach ($aAllFiles as $sKey => $aValue){
			if (stristr($sKey, "patch")){
				//check for content
				if (stristr($aValue['type'], "image")) {
					$sFileExt=strrchr($aValue['name'], '.');
					$sMoveName=Auth::$aUser['id']."_".uniqid().$sFileExt;
					@move_uploaded_file($aValue['tmp_name'], SERVER_PATH."/imgbank/Image/messages_attachment/".$sDate."/".$sMoveName);
					$aData=array(
						"file_link"=>"/imgbank/Image/messages_attachment/".$sDate."/".$sMoveName,
						"file_name"=>$aValue['name']);
					$aAttachFiles[]=$aData;
				}
			}	
		}
		
		return $aAttachFiles;
	}
	//---------------------------------------------------------------------------------------------------------

}
?>