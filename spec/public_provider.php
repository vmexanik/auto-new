<?php

$oObject=new PublicProvider();
$sPrefix='public_provider_';

switch (Base::$aRequest['action'])
{
	case $sPrefix.'add':
		$oObject->Add();
		break;
		
	case $sPrefix.'show':
		$oObject->Show();
		break;
		
	case $sPrefix.'create':
		$oObject->CreateExcel();
		break;
	default:
		$oObject->Index();
		break;
}

?>