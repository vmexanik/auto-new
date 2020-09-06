<?

//define(RU,"1");//for windows-1251

include_once (SERVER_PATH."/libp/xajax/xajax.inc.php");
//#################################################################################
//#################################################################################
function do_include($aFormValues="",$url="")
{
	$objResponse = new xajaxResponse();
	if (RU==1) $objResponse->setCharEncoding('windows-1251');

	// globalize form values
	if (is_array($aFormValues))	foreach ($aFormValues as $key => $value) Base::$aRequest[$key]=$value;

	// globalize url variables
	if ($url) {
		$url_string_array=parse_url($url);
		parse_str($url_string_array[query],$aUrl);
		Base::$aRequest=array_merge(Base::$aRequest,$aUrl);
	}

	Base::$oResponse=$objResponse;
	include_once(SERVER_PATH."/action_includer.php");

	return $objResponse->getXML();
}
//#################################################################################
function process_form($aFormValues)
{
	return do_include($aFormValues);
}
//#################################################################################
function process_browse_url($url)
{
	return do_include("",$url);
}
//#################################################################################

$xajax = new xajax();
if (RU==1) $xajax->setCharEncoding('windows-1251');
//$xajax->debugOn();
if (RU==1) $xajax->decodeUTF8InputOn();

$xajax->statusMessagesOn();
$xajax->errorHandlerOn();
//$xajax->setLogFile("../xajax/xajax_log/errors.log");

$xajax->registerFunction("process_form");
$xajax->registerFunction("process_browse_url");

$xajax->processRequests();

//$_main_template=str_replace("::xajax_javascript::", $xajax->getJavascript('xajax') ,$_main_template);
//#################################################################################

?>