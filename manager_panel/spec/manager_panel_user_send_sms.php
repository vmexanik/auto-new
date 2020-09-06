<?php 
/**
 * @author Vladimir Fedorov
 */

class AManagerPanelUserSendSms extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		if (!Base::$aRequest['id_user'] || !Base::$aRequest['id_cp']) 
			return;
		
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array('id'=>Base::$aRequest['id_cp'])));
		if (!$aCartPackage) 
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser) 
			return;
		Base::$tpl->assign('aUser',$aUser);
		
		if (Base::$aGeneralConf['is_sms_available'])
			Base::$tpl->assign('sms_available',1);
		
		Base::$oResponse->addAssign ( 'title_popup', 'innerHTML', Language::getMessage('send_sms'));
		$sBody = Base::$tpl->fetch('manager_panel/user_send_sms/form_send_sms_popup.tpl');
		
		Base::$oResponse->addAssign ( 'body_popup', 'innerHTML', $sBody );
		Base::$oResponse->addScript ("$('.block-popup').css({'width':'700','owerflow':'hidden'});");
		Base::$oResponse->addScript ("$('.js_manager_panel_popup').show();");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function AddText()
	{
		if (!Base::$aRequest['type'])
			return;
		switch (Base::$aRequest['type']) {
			case 'contacts':$sAdd = Language::getText('sms_contacts');break;
			case 'back_address':$sAdd = Language::getText('sms_back_address');break;
			case 'rekvisity':$sAdd = Language::getText('sms_rekvisity');break;
		}
		$sAdd = str_replace("\n","",$sAdd);
		$sAdd = str_replace("<br>","\\n",$sAdd);
		$sAdd = str_replace("<br />","\\n",$sAdd);
		Base::$oResponse->addScript ("$('#text_sms').val($('#text_sms').val()+'".$sAdd."')");
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function Send()
	{
		if (!Base::$aRequest['id_user'])
			return;
		
		$aUser = Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
		if (!$aUser)
			return;
		
		if (!Base::$aRequest['sms']) {
			$this->ManagerPanelMessage ('MT_ERROR',Language::getMessage('not_fill_sms'),'reg_error_popup');
			return;
		}
		if (!$aUser['phone']) {
			$this->ManagerPanelMessage ('MT_ERROR',Language::getMessage('not_fill_user_phone'),'reg_error_popup');
			return;
		}
		
		require_once(SERVER_PATH.'/class/core/Sms.php');
		Sms::AddDelayed($aUser['phone'],Base::$aRequest['sms']);

		$this->ManagerPanelMessage ('MT_SUCCESS',Language::getMessage('sms_sended'),'reg_error_popup');
	}
}