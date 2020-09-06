<?php

$oObject=new Delivery();
$sPrefix='delivery_';

switch (Base::$aRequest['action'])
{
	case $sPrefix.'set':
		$oObject->Set();
		break;

	default:
		$oObject->Index();
		break;
}


?>