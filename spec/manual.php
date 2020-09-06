<?php

$oObject=new Manual();
$sPreffix='manual_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'show':
		$oObject->Show();
		break;
	default:
		$oObject->Index();
		break;

}
?>