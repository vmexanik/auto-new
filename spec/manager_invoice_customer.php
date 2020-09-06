<?php

$sPrefix = 'manager_invoice_customer_';
$oObject = new ManagerInvoiceCustomer();
switch (Base::$aRequest ['action'])
{
	case $sPrefix.'create':
		$oObject->Create();
		break;

	case $sPrefix.'create_print':
		$oObject->Create(true);
		break;

	case $sPrefix.'invoice':
	case $sPrefix.'invoice_edit':
		$oObject->Invoice();
		break;

	case $sPrefix.'print':
		$oObject->PrintInvoice();
		break;

	case $sPrefix.'create_office_travel_sheet':
		$oObject->CreateOfficeTravelSheet();
		break;


	case $sPrefix.'is_travel_sheet_add':
		$oObject->IsTravelSheetAdd();
		break;

	case $sPrefix.'is_travel_sheet_clear':
		$oObject->IsTravelSheetClear();
		break;

	case $sPrefix.'is_travel_sheet_browse':
		$oObject->IsTravelSheetBrowse();
		break;

	case $sPrefix.'create_invoice_travel_sheet':
		$oObject->CreateInvoiceTravelSheet();
		break;

	case $sPrefix.'get_invoice_excel':
		$oObject->GetInvoiceExcel();
		break;

	case $sPrefix.'get_invoice_excel_filtered':
		$oObject->GetInvoiceExcelFitlered();
		break;

	case $sPrefix.'get_invoice_excel_all':
		$oObject->GetInvoiceExcelAll();
		break;

	case $sPrefix.'change_rating':
		$oObject->ChangeRating();
		break;

	case $sPrefix.'get_invoice_facture_excel':
		$oObject->GetInvoiceFactureExcel();
		break;

	case $sPrefix.'get_invoice_facture_excel_filtered':
		$oObject->GetInvoiceFactureExcelFitlered();
		break;

	case $sPrefix.'get_invoice_facture_excel_all':
		$oObject->GetInvoiceFactureExcelAll();
		break;

	case $sPrefix.'change_customer_type':
		$oObject->ChangeCustomerType();
		break;

	case $sPrefix.'get_invoice_list_excel':
		$oObject->GetInvoiceListExcel();
		break;

	case $sPrefix.'delivery_calculator':
	case $sPrefix.'delivery_calculator_apply':
		$oObject->DeliveryCalculator();
		break;

	case $sPrefix.'delivery':
	case $sPrefix.'delivery_apply':
		$oObject->Delivery();
		break;

	case $sPrefix.'delivery_update':
		$oObject->DeliveryUpdate();
		break;

	case $sPrefix.'delivery_edit':
		$oObject->DeliveryEdit();
		break;

	default :
		$oObject->Index();
		break;
}

?>