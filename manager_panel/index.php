<?php
include "../connect.php";

if ($_GET['action']=='quit') {
	session_start();
	$_SESSION["mmanager_auth".$GENERAL_CONF['ProjectName']]='';
	$_SESSION[mmanager_auth_browser]='';
}

include './manager.html';
?>