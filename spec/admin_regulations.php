<?php

$oObject=new AdminRegulations();
$sPrefix='admin_regulations_';

switch (Base::$aRequest['action'])
{
	case $sPrefix."sinxro_translate":
		$oObject->SinxroTranslate();
		break;
		
	case $sPrefix."insert_irbis":
		$oObject->InsertIrbis();
		break;

	case $sPrefix."get_from_irbis":
		$oObject->GetFromIrbis();
		break;
		
	default:
		$oObject->Index();
		break;
}
?>