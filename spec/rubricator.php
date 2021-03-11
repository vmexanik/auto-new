<?php

$oObject=new Rubricator();
$sPrefix='rubricator_';
switch (Base::$aRequest['action'])
{
	case $sPrefix.'category':
		$oObject->Category();
		break;

	case $sPrefix.'set_make':
		$oObject->SetMake();
		break;	
				
	case $sPrefix.'set_model':
		$oObject->SetModel();
		break;
		
	case $sPrefix.'set_model_detail':
		$oObject->SetModelDetail();
		break;
		
	case $sPrefix.'set_all':
		$oObject->SetAll();
		break;
		
	case $sPrefix.'get_part':
		$oObject->GetPart();
		break;

	default:
		$oObject->Index();
		break;
}
?>