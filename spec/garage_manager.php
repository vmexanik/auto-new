<?php


$sPrefix='garage_manager_';
$oObject=new GarageManager();


switch (Base::$aRequest['action'])
{
	case $sPrefix.'edit':
	case $sPrefix.'add':
		$oObject->Edit();
		break;

	case $sPrefix.'add_comment':
		$oObject->AddComment();
		break;
		
	case $sPrefix.'delete':
		$oObject->Delete();
		break;
		
	case $sPrefix.'profile':
		$oObject->Profile();
		break;
		
	default:
		$oObject->Index();
		break;
}


?>