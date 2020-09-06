<?

require_once('connect.php');/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
session_start();
require('init.php');

if (Base::$aRequest['xajax']) {
	require( SERVER_PATH.'/xajax_request_parser.php');
}
else {
	require( SERVER_PATH.'/action_includer.php');
	Base::Process();
}

?>