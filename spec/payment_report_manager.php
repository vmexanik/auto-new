<?php

require_once(SERVER_PATH.'/class/module/PaymentReportManager.php'); ;
$oObject=new PaymentReportManager();
$sPreffix='payment_report_manager_';

switch (Base::$aRequest['action'])
{
/*
	case $sPreffix.'add':
	case $sPreffix.'edit':		
		$oPaymentReport->Add();
		break;
		
	case $sPreffix.'delete':
		$oPaymentReport->Delete();
		break;
			
*/
	default:
		$oObject->Index();
		break;

}
?>