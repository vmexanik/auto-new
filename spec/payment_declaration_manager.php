<?php

require_once(SERVER_PATH.'/class/module/PaymentDeclarationManager.php'); ;
$oObject=new PaymentDeclarationManager();
$sPreffix='payment_declaration_manager_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'add':
	case $sPreffix.'edit':		
		$oObject->Add();
		break;
		
	case $sPreffix.'delete':
		$oObject->Delete();
		break;
		
	case $sPreffix.'select_user':
		$oObject->SelectUser();
		break;
	
	default:
		$oObject->Index();
		break;

}
?>