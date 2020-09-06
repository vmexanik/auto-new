<?php

require_once(SERVER_PATH.'/class/module/PriceProfile.php'); ;
$sPreffix='price_profile_';
$oPrice=new PriceProfile();

switch (Base::$aRequest['action'])
{
	case $sPreffix.'item_edit':
		$oPrice->ItemEdit();
		break;	
	case $sPreffix.'item_delete':
		$oPrice->ItemDelete();
		break;

	case $sPreffix.'item_apply':
		$oPrice->ItemApply();
		break;	
		
	case $sPreffix.'load':
		$oPrice->LoadFromFile();
		break;	
		
	case $sPreffix.'item_print':
		$oPrice->ItemPrint();
		break;
		
	case $sPreffix.'provider_add':
		$oPrice->PopUpProviderAdd();
		break;
		
	case $sPreffix.'add_from_file':
	case $sPreffix.'add_from_file_submit':
	case $sPreffix.'add_from_file_submit_queue':
		$oPrice->ProviderAddFromFile();
		break;
		
	case $sPreffix.'change_view_loaded_price':
		$oPrice->ReloadViewPrice();
		break;
		
	case $sPreffix.'provider_edit':
		$oPrice->ProviderEdit();
		break;
		
	default:
		$oPrice->Index();
		break;

}


?>