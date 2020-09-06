<?php

$oObject=new Store();
$sPreffix='store_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'input_invoice_manual':
	case $sPreffix.'input_invoice_manual_add':
	case $sPreffix.'input_invoice_manual_delete':
		$oObject->InputInvoiceManual();
		break;
		
	case $sPreffix.'input_invoice_manual_process':
		$oObject->InputInvoiceProcess();
		break;
		
	case $sPreffix.'input_invoice_scanner':
		$oObject->InputInvoiceScanner();
		break;
		
	case $sPreffix.'store':
	case $sPreffix.'store_add':
		$oObject->Store();
		break;
		
	case $sPreffix.'sale':
		$oObject->Sale();
		break;
		
	case $sPreffix.'sale_invoice':
	case $sPreffix.'sale_invoice_delete':
		$oObject->SaleInvoice();
		break;
		
	case $sPreffix.'sale_invoice_process':
		$oObject->SaleInvoiceProcess();
		break;
		
	case $sPreffix.'log':
		$oObject->Log();
		break;
		
	case $sPreffix.'log_history':
		$oObject->LogHistory();
		break;
		
	case $sPreffix.'update_number':
		$oObject->UpdateNumber();
		break;
		
	case $sPreffix.'transfer':
	case $sPreffix.'transfer_delete':
		$oObject->Transfer();
		break;
	
	case $sPreffix.'add_to_sale':
		$oObject->AddToSale();
		break;
		
	case $sPreffix.'add_to_transfer':
		$oObject->AddToTransfer();
		break;
		
	case $sPreffix.'transfer_process':
		$oObject->TransferProcess();
		break;
		
	case $sPreffix.'balance':
		$oObject->Balance();
		break;
		
	case $sPreffix.'return':
		$oObject->ReturnStore();
		break;
		
	case $sPreffix.'export_to_price':
		$oObject->ExportToPrice();
		break;
		
	case $sPreffix.'products':
	case $sPreffix.'products_add':
	case $sPreffix.'products_edit':
		$oObject->Products();
		break;
	
	default:
		$oObject->Index();
		break;

}
?>