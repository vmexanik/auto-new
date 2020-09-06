<?php

$oObject=new PriceGroup();

switch (Base::$aRequest['action'])
{
	case $oObject->sPrefix.'_filter':
		$oObject->Filter();
		break;
	
	default:
		$oObject->Index();
		break;
}
?>