<?php

$oObject=new ManagerCart();
$sPreffix='manager_cart_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'archive':
		$oObject->Archive();
		break;

	case $sPreffix.'store':
		$oObject->Store();
		break;

	case $sPreffix.'payment':
	case $sPreffix.'payment_add':
	case $sPreffix.'payment_edit':
	case $sPreffix.'payment_apply':
	case $sPreffix.'payment_pay':
	case $sPreffix.'payment_delete':
		$oObject->Payment();
		break;

	case $sPreffix.'add':
	case $sPreffix.'edit':
	case $sPreffix.'delete':
	default:
		$oObject->Index();
		break;

}
?>