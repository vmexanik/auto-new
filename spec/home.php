<?php

$oObject=new Home();
$sPrefix='home_';

switch (Base::$aRequest['action'])
{
	default:
		$oObject->Index();
		break;

}
?>