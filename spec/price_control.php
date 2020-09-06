<?php
$oObject = new PriceControl();
$sPrefix = $oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
    case $sPrefix.'change_code_add':
	case $sPrefix.'change_code':
	case $sPrefix.'change_code_del':
	    $oObject->ChangeCode();
		break;
		
	case $sPrefix.'change_code_edit':
	    $oObject->ChangeCodeEdit();
	    break;
	    
	case $sPrefix.'edit_code':
	    $oObject->EditCode();
		break;

	case $sPrefix.'change_code_import':
	    $oObject->ChangeCodeImport();
	    break;
	    
    case $sPrefix.'update_parse':
        $oObject->UpdateCatParse();
        break;
        
    case $sPrefix.'locked_code':
        $oObject->LockedCode();
        break;
        
    case $sPrefix.'unlocked_code':
        $oObject->UnLockedCode();
        break;
        
    case $sPrefix.'delete_items':
        $oObject->DeleteItems();
        break;
        
    case $sPrefix.'delete_filtered_items':
        $oObject->DeleteFilteredItems();
        break;
        
    case $sPrefix.'update_select_provider':
        $oObject->UpdateSelectProvider();
        break;
        
    case $sPrefix.'addtoprice_items':
        $oObject->AddToPticeItems();
        break;
        
    case $sPrefix.'change_code_pi':
        $oObject->ChangeCodePi();
        break;
        
    case $sPrefix.'edit_code_pi':
        $oObject->EditCodePi();
        break;
        
    case $sPrefix.'check_code':
        $oObject->CheckCode();
        break;
        
    case $sPrefix.'locked_code_one':
        $oObject->LockedCodeOne();
        break;
        
    case $sPrefix.'check_code_e':
        $oObject->CheckCodeE();
        break;
        
    case $sPrefix.'edit_code_add_product':
        $oObject->EditCodeAddProduct();
        break;
        
    case $sPrefix.'add_product':
        $oObject->AddProduct();
        break;
        
    case $sPrefix.'set_brand':
        $oObject->SetBrand();
        break;
        
    case $sPrefix.'set_newbrand':
        $oObject->SetNewBrand();
        break;
        
    case $sPrefix.'set_code':
        $oObject->SetCode();
        break;

    case $sPrefix.'set_price':
        $oObject->SetPrice();
        break;
        
    case $sPrefix.'set_name':
        $oObject->SetName();
        break;

    case $sPrefix.'set_provider':
        $oObject->SetProvider();
        break;
        
    case $sPrefix.'check_install_cat_parse':
        $oObject->CheckInstallCatParse();
        break;
        
	default:
	case $sPrefix.'system':
		$oObject->Index();
		break;
}
?>