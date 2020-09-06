<?php

$oObject=new Buh();
$sPrefix=$oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
	case $sPrefix."changeling":
		$oObject->Changeling();
		break;

	case $sPrefix."close_month":
		$oObject->CloseMonth();
		break;
	
	/*case $sPrefix.'change_form':
		$oObject->ChangeForm();
		break;*/
		
	case  $sPrefix.'changeling_preview':
		$oObject->ChangelingPreview();
		break;

	case  $sPrefix.'add_amount':
	case  $sPrefix.'edit_amount':
		$oObject->AddAmount();
		break;

	case $sPrefix.'get_subconto':
		$oObject->GetSubconto();
		break;

	default:
		$oObject->Index();
		break;
}
?>