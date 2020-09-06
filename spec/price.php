<?php
$oObject = new Price();
$sPrefix = $oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
	case $sPrefix.'load':
		$oObject->LoadFromFile();
		break;

	case $sPrefix.'install':
		$oObject->Install();
		break;

	case $sPrefix.'conformity':
		$oObject->Conformity();
		break;

	case $sPrefix.'conformity_apply':
	case $sPrefix.'conformity_auto':
		$oObject->ConformityApply();
		break;
		
	case $sPrefix.'auto_assoc_cat':
		$oObject->AutoAssocCat();
		break;

	case $sPrefix.'clear_import':
		$oObject->ClearImport();
		break;

	case $sPrefix.'clear_pref':
		$oObject->ClearPref();
		break;

	case $sPrefix.'clear_provider':
		$oObject->ClearProvider();
		break;

	case $sPrefix.'export':
		$oObject->Export();
		break;

	case $sPrefix.'file_export':
		$oObject->ExportFile();
		break;

	case $sPrefix.'add_cat':
		$oObject->AddCat();
		break;
	
	case $sPrefix.'refresh_queue':
		$oObject->RefreshQueue();
		break;
		
	case $sPrefix.'remove_pref':
		$oObject->RemovePref();
		break;
		
	case $sPrefix.'add_auto_pref':
		$oObject->AddAutoPref();
		break;
		
	case $sPrefix.'add_new':
		$oObject->AddNewPriceItem();
		break;

	default:
		$oObject->Index();
		break;
}
?>