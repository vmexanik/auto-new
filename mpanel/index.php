<?php
include "../connect.php";

if ($_GET['action']=='quit') {
	session_start();
	$_SESSION["mpanel_auth".$GENERAL_CONF['ProjectName']]='';
	$_SESSION[mpanel_auth_browser]='';
}

    include './old.html';
?>