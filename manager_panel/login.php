<?
session_start();

require "../connect.php";

$bIsManagerRequest=true;

require "../init.php";

if ($_REQUEST['auth'])
{
	if (ManagerPanel::IsManager($_REQUEST['login'],$_REQUEST['password']))
	{
		$_SESSION["mmanager_auth".Base::$aGeneralConf['ProjectName']]=$_REQUEST['login'];
		$_SESSION["mmanager_auth_browser"]="ok";
		Header("Location: login.php");
		die();
	}
	else {
		Header("Location: ./?auth=bad");
		die();
	}
}

//Remarked for Session validation in ajax calls
if (!$_SESSION["mmanager_auth".Base::$aGeneralConf['ProjectName']]&&!$xajax)  {
	if ($_REQUEST['xajax']) {
		include_once (SERVER_PATH ."/libp/xajax/xajax.inc.php");
		Base::$oResponse = new xajaxResponse();
		Base::$oResponse->addScript("if (confirm('Invalid Session: redirect to login page?')) {window.location='./?auth=bad'}");
		Header('Content-type: text/xml; charset=windows-1251');
		die(Base::$oResponse->getXML());
	}
	else {
		Header("Location: ./?auth=bad");
		die();
	}
}


//####################################################################################
if (Base::$aRequest['action']=='home' && !Base::$aRequest['xajax']) {
	Base::$tpl->assign('aMmanager', Base::$db->GetRow("select * from user
		where login='".$_SESSION["mmanager_auth".Base::$aGeneralConf['ProjectName']]."'"));
}
if (Base::$aRequest['xajax']) {
	include(SERVER_PATH.'/include/manager_panel/xajax_request_parser_manager_panel.php');
}
else include(SERVER_PATH.'/include/manager_panel/manager_panel_includer.php');

if (Base::$aRequest['action']=='home') Base::$sText='';

$sHeadAdditional="
    <script src='/libp/jquery/jquery-1.11.3.min.js'></script>
    <link href='/css/select2.min.css' rel='stylesheet' />
    <script src='/js/select2.min.js'></script>";
Base::$tpl->assign('sHeadAdditional',$sHeadAdditional);

Base::$tpl->assign('sProjectName',Base::$aGeneralConf['ProjectName']);
Base::$tpl->assign('sCharSet',$const_meta_charset);
Base::$tpl->assign('sMainUrlHttp',"http://$_SERVER_NAME/");
Base::$tpl->assign('sManagerPanelVersion',Base::$aGeneralConf['ManagerPanelVersion']);

ManagerPanel::InitMP();
ManagerPanel::CreateTopMenu();
$sTopMenu = Base::$tpl->fetch('manager_panel/top_menu.tpl');

Base::$tpl->assign('sTopMenu',$sTopMenu);
Base::$tpl->assign('sText',Base::$sText);


require(SERVER_PATH.'/class/core/XajaxParser.php');
Base::$tpl->assign('sXajaxJavascript', $sXajaxJavascript);

echo Base::$tpl->fetch('manager_panel/login.tpl');

?>