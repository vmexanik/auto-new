<?php

$oObject=new ExtensionTD();
$sPrefix=$oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{		
	case $sPrefix."tree":
		$oObject->Tree();
		break;
		
	case $sPrefix."tree_change_select":
		$oObject->TreeChangeSelect();
		break;
		
	case $sPrefix."tree_add":
		$oObject->TreeAdd();
		break;
		
	case $sPrefix."tree_part":
		$oObject->TreePart();
		break;
		
	case $sPrefix."tree_part_copy":
		$oObject->TreePartCopy();
		break;
		
	case $sPrefix."tree_part_copy_process":
		$oObject->TreePartCopyProcess();
		break;
		
	case $sPrefix."cat_info_import":
	case $sPrefix."cat_info_import_add":
	case $sPrefix."cat_info_import_edit":
	    $oObject->CatInfoImport();
	    break;
	
	case $sPrefix."cat_info_import_delete":
	    $oObject->CatInfoImportDelete();
	    break;
	
	case $sPrefix."cat_info_import_set":
	    $oObject->CatInfoImportSet();
	    break;
	
	case $sPrefix."cat_info_import_set_characteristic":
	    $oObject->CatInfoImportSetCharacteristic();
	    break;
	
	case $sPrefix."cat_info_import_set_image":
	    $oObject->CatInfoImportSetImage();
	    break;
	
	case $sPrefix."cat_info_import_set_cross":
	    $oObject->CatInfoImportSetCross();
	    break;
	
	case $sPrefix."cat_info_import_set_applicability":
	    $oObject->CatInfoImportSetApplicability();
	    break;
    
    case $sPrefix."history_image":
        $oObject->HistoryImage();
        break;
    
    case $sPrefix."history_characteristic":
        $oObject->HistoryCharacteristic();
        break;
    
    case $sPrefix."history_cross":
        $oObject->HistoryCross();
        break;
    
    case $sPrefix."history_applicability":
        $oObject->HistoryApplicability();
        break;
    
    case $sPrefix."history_image_delete":
        $oObject->HistoryImageDelete();
        break;
    
    case $sPrefix."history_characteristic_delete":
        $oObject->HistoryCharacteristicDelete();
        break;
    
    case $sPrefix."history_cross_delete":
        $oObject->HistoryCrossDelete();
        break;
    
    case $sPrefix."history_applicability_delete":
        $oObject->HistoryApplicabilityDelete();
        break;
    
    case $sPrefix."history_tree":
        $oObject->HistoryTree();
        break;
    
    case $sPrefix."history_tree_delete":
        $oObject->HistoryTreeDelete();
        break;
        
    case $sPrefix."tree_rubric":
        $oObject->TreeRubric();
        break;
    
    case $sPrefix."tree_rubric_delete":
        $oObject->TreeRubricDelete();
        break;
    
    case $sPrefix."tree_rubric_import":
        $oObject->TreeRubricImport();
        break;
    
    case $sPrefix."tree_rubric_export":
        $oObject->TreeRubricExport();
        break;

	default:
		$oObject->Index();
		break;
}
?>