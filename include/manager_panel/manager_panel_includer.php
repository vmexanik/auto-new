<?
include 'manager_panel_action_array.php';

krsort($action_array, SORT_STRING);

$sMethod='Index';

$global_include = array();
$curract = Base::$aRequest['action'];
foreach ($action_array as $action_key => $action_value)
{
	$action_parts = explode('*', $action_key);
	$hasAll = true;
	$f = true;
	foreach ($action_parts as $action_part)
	{
		if (strlen(trim($action_part)) > 0)
		{
			$spos = strpos($curract,$action_part);
			if ($spos === false || (($spos > 0) && ($f == true)))
			{
				$hasAll = false;
			}
			$f = false;
		}
	}
	if ($hasAll == true)
	{
		foreach ($global_include as $gi)
		{
			include_once('spec/'.$gi);
		}

		if (file_exists(SERVER_PATH.'/manager_panel/spec/'.$action_value) ) 
			include_once(SERVER_PATH.'/manager_panel/spec/'.$action_value);

		$sActionBase=substr($action_value,0,stripos($action_value,'.php'));
		if (strpos(Base::$aRequest['action'],'_')) {
			$sActionMethod=substr(Base::$aRequest['action'],strlen($sActionBase) );
			$aActionMethod=explode('_',trim($sActionMethod,'_'));
			if ($aActionMethod ) {
				$sMethod='';
				foreach ($aActionMethod as $value) $sMethod.=ucfirst($value);
			}
		}

		$sClass='A'.Admin::ActionToClass($sActionBase);
		if (class_exists($sClass)) {
			$oObject=new $sClass();
			if (method_exists($oObject,$sMethod)) $oObject->$sMethod();
			else $oObject->Index();
		}

		if (Base::$aRequest['action']!='manager_panel_manager_package_list_view_logcp')
			Base::$oResponse->addScript ("$('.js_manager_panel_popup_log').hide();");
		
		// active menu
		if (Base::$aRequest['change_top_menu']) {
			Base::$oResponse->addScript ( '$("[id^=manager_panel_]").removeClass("active")');
			Base::$oResponse->addScript ( '$("#'.Base::$aRequest['action'].'").addClass("active")');
		}
		// reload select styles
		Base::$oResponse->addScript ("$('.selectpicker').selectpicker('refresh');");
		
		if (Base::$aRequest['tooltip'])
			Base::$oResponse->addScript ("$('[data-toggle=\"tooltip\"]').tooltip({'html':true});");
		
		if (Base::$aMessageJavascript && count(Base::$aMessageJavascript) >0 ) {
			Base::$tpl->assign('aMessageJavascript', Base::$aMessageJavascript);
			Base::$sOuterJavascript.= Base::$tpl->fetch ("message_input.tpl");
			Base::$oResponse->addAssign ( 'message_javascript', 'innerHTML', Base::$sOuterJavascript );
		}
		
		break;
	}
}
?>