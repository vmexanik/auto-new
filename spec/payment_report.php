<?php

require_once(SERVER_PATH.'/class/module/PaymentReport.php'); ;
$oPaymentReport=new PaymentReport();
$sPreffix='payment_report_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'add':
	case $sPreffix.'edit':		
		$oPaymentReport->Add();
		break;
		
	case $sPreffix.'delete':
		$oPaymentReport->Delete();
		break;
			
	default:
		$oPaymentReport->Index();
		break;

}
?>