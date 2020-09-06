<?php

$sPrefix = 'dashboard_';
$oObject = new Dashboard();

switch (Base::$aRequest ['action'])
{
	default :
		$oObject->Index();
		break;

}

?>