<?
session_start();

require "../connect.php";

$bIsMpanalRequest=true;

require "../init.php";

if ($_REQUEST['auth'])
{
	if (Admin::IsMpanelUser($_REQUEST['login'],$_REQUEST['password']))
	{
		$_SESSION["mpanel_auth".Base::$aGeneralConf['ProjectName']]=$_REQUEST['login'];
		$_SESSION[mpanel_auth_browser]="ok";
		Header("Location: login.php");
		die();
	}
	else {
		Header("Location: ./?auth=bad");
		die();
	}
}

//Remarked for Session validation in ajax calls
if (!$_SESSION["mpanel_auth".Base::$aGeneralConf['ProjectName']]&&!$xajax)  {
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
	Base::$tpl->assign('aAdmin', Base::$db->GetRow("select * from admin
		where login='".$_SESSION["mpanel_auth".Base::$aGeneralConf['ProjectName']]."'"));

	$action="splash_xajax";
	include_once(SERVER_PATH."/class/core/mpanel/spec/splash.php");
	$oSplash=new ASplash();
	$oSplash->Index();
}

if (Base::$aRequest['xajax'] || Base::$aRequest['action']=='splash_xajax') {
	include(SERVER_PATH.'/class/core/mpanel/xajax_request_parser.php');
}
else include(SERVER_PATH.'/class/core/mpanel/includer.php');

$sHeadAdditional= "
    <link href='/css/select2.min.css' rel='stylesheet' />
    <link href='/libp/mpanel/css/tecdoc_filter.css' rel='stylesheet' />
    <script src='/js/select2.min.js'></script>
    <script src='/libp/mpanel/js/tecdoc_filter.js'></script>
";
Base::$tpl->assign('sHeadAdditional',$sHeadAdditional);

Base::$tpl->assign('sProjectName',Base::$aGeneralConf['ProjectName']);
Base::$tpl->assign('sMpanelVersion',Base::$aGeneralConf['MpanelVersion']);
Base::$tpl->assign('sCharSet',$const_meta_charset);
Base::$tpl->assign('sMainUrlHttp',"http://$_SERVER_NAME/");

Base::$tpl->assign('sText',Base::$sText);

require(SERVER_PATH.'/class/core/XajaxParser.php');
Base::$tpl->assign('sXajaxJavascript', $sXajaxJavascript);

// if(file_exists(SERVER_PATH."/template/mpanel/dtree_new.tpl")) {
    echo Base::$tpl->fetch('addon/mpanel/login_new.tpl');
// } else {
//     echo Base::$tpl->fetch('addon/mpanel/login.tpl');
// }

?>