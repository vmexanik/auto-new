<?php
$sPrefix='cart_';
$oObject=new Cart(true);

switch (Base::$aRequest['action'])
{
	case $sPrefix.'cart':
	case $sPrefix.'cart_add':
	case $sPrefix.'cart_edit':
	case $sPrefix.'cart_delete':
	case $sPrefix.'cart_clear':
		$oObject->CartList();
		break;
	
	case $sPrefix.'onepage_order':
		$oObject->CartOnePageOrder();
		break;

	case $sPrefix.'onepage_order_manager':
		$oObject->CartOnePageOrderManager();
		break;
	
	case $sPrefix.'cart_print':
		$oObject->CartPrint();
		break;

	case $sPrefix.'cart_update_number':
		$oObject->CartUpdateNumber();
		break;

	case $sPrefix.'add_cart_item_checked':
		$oObject->AddCartItemChecked();
		break;

	case $sPrefix.'add_cart_item':
		$oObject->AddCartItem(1,false);
		break;

	case $sPrefix.'order':
	case $sPrefix.'order_edit':
	case $sPrefix.'order_log':
		$oObject->OrderList();
		break;

	case $sPrefix.'package_list':
	case $sPrefix.'package_edit':
		$oObject->PackageList();
		break;

	case $sPrefix.'package_delete':
		$oObject->PackageDelete();
		break;

		// Step1 - package confirm
	case $sPrefix.'package_confirm':
		$oObject->PackageConfirm();
		break;

		// Step2 - check account
	case $sPrefix.'check_account':
		$oObject->CheckAccount();
		break;
	case $sPrefix.'select_account':
		$oObject->SelectAccount();
		break;

		// Step3 - Shipment_details
	case $sPrefix.'shipment_detail':
		$oObject->ShipmentDetail();
		break;

		// Step4 - Payment Method
	case $sPrefix.'payment_method':
		$oObject->PaymentMethod();
		break;

		// Step5 - End
	case $sPrefix.'payment_end':
		$oObject->PaymentEnd();
		break;

	case $sPrefix.'payment_end_button':
		$oObject->PaymentEndButton();
		break;

	case $sPrefix.'package_print':
		$oObject->PackagePrint();
		break;
	
	case $sPrefix.'get_ownauto':
		$oObject->PopUpGetOwnAuto();
		break;

	case $sPrefix.'expired_info':
		$oObject->CartExpiredInfo();
		break;
		
	case $sPrefix.'order_by_phone':
		$oObject->OrderByPhone();
		break;
		
	case $sPrefix.'show_popup_cart':
	    $oObject->ShowPopupCart();
	    break;

	default:
		$oObject->Index();
		break;
}


?>