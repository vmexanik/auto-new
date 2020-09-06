<?php

require_once(SERVER_PATH.'/class/module/PaymentDeclaration.php'); ;
$oPaymentDeclaration=new PaymentDeclaration();
$sPreffix='payment_declaration_';

switch (Base::$aRequest['action'])
{
	default:
		$oPaymentDeclaration->Index();
		break;

}
?>