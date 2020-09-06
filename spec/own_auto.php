<?php

require_once(SERVER_PATH.'/class/module/OwnAuto.php'); ;
$sPrefix='own_auto_';
$oObject=new OwnAuto();

switch (Base::$aRequest['action'])
{
	case $sPrefix."json_get":
		$oObject->GetJson();
		break;
	case $sPrefix."json_add":
		$oObject->AddJson();
		break;
	case $sPrefix."del":
		$oObject->Del();
		break;
	case $sPrefix."search_log":
		$oObject->SearchLog();
		break;
	case $sPrefix."del_from_log":
		$oObject->DelFromAutoLog();
		break;
	case $sPrefix."add_garage":
		$oObject->AddGarageFromAutoLog();
		break;
		
	case $sPrefix."edit":	
	default:
		$oObject->Index();
		break;

}
?>