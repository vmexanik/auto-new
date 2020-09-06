<?php

$sPrefix='vin_request_';

$oObject=new VinRequest();

switch (Base::$aRequest['action'])
{
	case $sPrefix.'preview':
		$oObject->Preview();
		break;

	case $sPrefix.'manager_edit':
	case $sPrefix.'manager':
		$oObject->Manager();
		break;

	case $sPrefix.'manager_refuse':
		$oObject->ManagerRefuse();
		break;

	case $sPrefix.'manager_release':
		$oObject->ManagerRelease();
		break;

	case $sPrefix.'manager_save':
		$oObject->ManagerSave();
		break;

	case $sPrefix.'manager_send':
		$oObject->ManagerSend();
		break;

	case $sPrefix.'manager_remember':
		$oObject->ManagerRemember();
		break;

	case $sPrefix.'manager_delivery':
		$oObject->ManagerDelivery();
		break;

	case $sPrefix.'manager_send_preview':
		$oObject->ManagerSendPreview();
		break;

	case $sPrefix.'package_create':
		$oObject->PackageCreate();
		break;

	case $sPrefix."change_select":
		$oObject->ChangeSelect();
		break;
		
	case $sPrefix."change_select_own_auto":
		$oObject->ChangeSelectOwnAuto();
		break;
				
	default:
		$oObject->Index();
		break;
}


?>