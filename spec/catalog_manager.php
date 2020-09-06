<?php

$oObject=new CatalogManager();
$sPrefix=$oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
	case $sPrefix."upload_pic":
		$oObject->UploadPic();
		break;

	case $sPrefix."upload_make_code_pic":
		$oObject->UploadMakeCodePic();
		break;
	
	case  $sPrefix."pic_list":
		$oObject->ViewPicList();
		break;

	case $sPrefix."add_info":
		$oObject->AddInfo();
		break;		

	case $sPrefix."delete_info":
		$oObject->DeleteInfo();
		break;		

	case $sPrefix."delete_pic":
	case $sPrefix."edit_pic":
		$oObject->EditPic();
		break;		

	case $sPrefix."edit_name":
		$oObject->EditName();
		break;		

	case $sPrefix."add_pic_from_dir":
		$oObject->AddPicFromDir();
		break;		

	case $sPrefix."add_cat_info":
		$oObject->AddCatInfo();
		break;

	case $sPrefix."update_number":
		$oObject->UpdateNumber();
		break;
		
	case $sPrefix."upload_many_pics":
		$oObject->UploadManyPics();
		break;
		
	case $sPrefix."change_krit":
	    $oObject->ChangeKrit();
	    break;

	default:
		$oObject->Index();
		break;
}
?>