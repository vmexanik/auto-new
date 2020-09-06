<?php

require_once(SERVER_PATH.'/class/module/Finance.php'); ;
$sPreffix='finance_';
$oObject=new Finance();

switch (Base::$aRequest['action'])
{
	case $sPreffix.'bill':
	case $sPreffix.'bill_add':
	case $sPreffix.'bill_edit':
	case $sPreffix.'bill_delete':
		$oObject->Bill();
		break;

	case $sPreffix.'bill_pay':
		$oObject->BillPay();
		break;

	case $sPreffix.'bill_print':
		$oObject->BillPrint();
		break;
		
	case $sPreffix.'user':
		//$oObject->BillforUser();
		$oObject->FinanceUser();
		break;

	case $sPreffix.'cart_pay':
		$oObject->CartPay();
		break;

	case $sPreffix.'payforaccount':
		$oObject->PayForAccount();
		break;

	case $sPreffix.'export_all':
		$oObject->ExportAll();
		break;
		
	case $sPreffix.'customer':
	    $oObject->FinanceCustomer();
	    break;
	
	case $sPreffix.'customer_export':
	    $oObject->FinanceCustomerExport();
	    break;
	    
    case $sPreffix.'user_export':
    	$oObject->FinanceUserExport();
    	break;
    	
	case $sPreffix.'provider':
	    $oObject->FinanceProvider();
	    break;
	
	case $sPreffix.'provider_export':
	    $oObject->FinanceProviderExport();
	    break;
	
	case $sPreffix.'profit':
	    $oObject->Profit();
	    break;
	
	case $sPreffix.'profit_export':
	    $oObject->FinanceProfitExport();
	    break;
	    
    case $sPreffix.'add_deposit':
	    $oObject->AddDeposit();
	    break;
	    
    case $sPreffix.'customer_set_manager':
    	$oObject->SetManager();
    	break;
	    	 
    case $sPreffix.'correct_balance':
    	$oObject->CorrectBalance();
    	break;
    	
    case $sPreffix.'customer_set_custom_id':
    	$oObject->FinanceCustomerSetCustomId();
    	break;

    case $sPreffix.'reestr_pko':
    case $sPreffix.'reestr_rko':
    case $sPreffix.'reestr_bv':
   		$oObject->Reestr();
   		break;
   		
   	case $sPreffix.'reestr_provider_pko':
   	case $sPreffix.'reestr_provider_rko':
   	case $sPreffix.'reestr_provider_bv':
   		$oObject->ReestrProvider();
   		break;
   			
   	case $sPreffix.'bill_provider':
   	case $sPreffix.'bill_provider_add':
   	case $sPreffix.'bill_provider_edit':
   	case $sPreffix.'bill_provider_delete':
   		$oObject->Provider();
   		break;
   		
   	case $sPreffix.'bill_provider_print':
   		$oObject->BillProviderPrint();
   		break;
   		
	default:
		$oObject->Bill();
		//$oObject->Index();
		break;

}
?>