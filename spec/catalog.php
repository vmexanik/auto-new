<?php

$oObject=new Catalog();
$sPrefix='catalog_';

switch (Base::$aRequest['action'])
{
// 	case $sPrefix."model_view":
// 		$oObject->ViewModel();
// 		break;
		
// 	case $sPrefix."model_group_view":
// 	    $oObject->ViewModelGroup();
// 	    break;

// 	case $sPrefix."detail_model_view":
// 		$oObject->ViewModelDetail(true);
// 		break;

	case $sPrefix."model_for":
		$oObject->ModelFor();
		break;

	case $sPrefix."view_map":
		$oObject->ViewMap();
		break;

	case $sPrefix."assemblage_view":
	case $sPrefix."part_view":
		$oObject->ViewAssemblage();
		break;

	case $sPrefix."part_info_view":
		$oObject->ViewInfoPart();
		break;

	case $sPrefix."price_view":
		$oObject->ViewPrice();
		break;

	case $sPrefix."price_export":
		$oObject->ExportPrice();
		break;

	case $sPrefix."json_get":
		$oObject->GetJson();
		break;

	case $sPrefix."set_image_width":
		$oObject->SetImageWidth();
		break;

	case $sPrefix."cross":
	case $sPrefix."cross_add":
	case $sPrefix."cross_edit":
	case $sPrefix."cross_apply":
		$oObject->Cross();
		break;
		
	case $sPrefix."cross_install":
		$oObject->CrossInstall();
		break;
		
	case $sPrefix."cross_load":
		$oObject->CrossLoad();
		break;
		
	case $sPrefix."cross_import_advance":
		$oObject->CrossImportAdvance();
		break;
		
	case $sPrefix."cross_profile":
	case $sPrefix."cross_profile_add":
	case $sPrefix."cross_profile_edit":
	case $sPrefix."cross_profile_delete":
		$oObject->CrossProfile();
		break;
		
	case $sPrefix."cross_clear_import":
		$oObject->CrossClearImport();
		break;
	
	case $sPrefix."cross_delete":
		$oObject->DeleteCross();
		break;

	case $sPrefix."cross_filtered_delete":
		$oObject->DeleteGroupCross();
		break;

	case $sPrefix."cross_import":
		$oObject->ImportCross();
		break;

	case $sPrefix."cross_stop":
	case $sPrefix."cross_stop_add":
	case $sPrefix."cross_stop_edit":
	case $sPrefix."cross_stop_apply":
		$oObject->CrossStop();
		break;
		
	case $sPrefix."cross_stop_delete":
		$oObject->DeleteCrossStop();
		break;

	case $sPrefix."cross_stop_filtered_delete":
		$oObject->DeleteGroupCrossStop();
		break;

	case $sPrefix."view_brand":
		$oObject->ViewBrand();
		break;

	case $sPrefix."price_search_log":
		$oObject->PriceSearchLog();
		break;

	case $sPrefix."change_select":
		$oObject->ChangeSelect();
		break;	

	case $sPrefix."autotechnics":
		$oObject->LoadAutotechnics();
		break;	
		
	case $sPrefix."original_cross":
		$oObject->OriginalCross();
		break;
		
	case $sPrefix."view_own_auto":
		$oObject->ViewOwnAuto();
		break;

	case $sPrefix."change_part_param":
		$oObject->ChangePartParam();
		break;

	case $sPrefix."original":
	    $oObject->Original();
	    break;

	default:
		$oObject->Index();
		break;
}
?>