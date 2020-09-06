<?php

$sPreffix='payment_';
$oObject=new Payment();

switch (Base::$aRequest['action'])
{
	case $sPreffix.'liqpay_result':
		$oObject->LiqpayResult();
		break;

	case $sPreffix.'liqpay_success':
		$oObject->LiqpaySuccess();
		break;


	default:
		$oObject->Index();
		break;
}


?>