<?php

$oObject=new LevamCatalog();
$sPrefix='levam_';

switch (Base::$aRequest['action'])
{
	case $sPrefix."change_mark":
	    $oObject->ChangeMark();
	    break;
	    
	case $sPrefix."change_model":
	    $oObject->ChangeModel();
	    break;
	    
	case $sPrefix."change_param":
	    $oObject->ChangeParam();
	    break;
	    
	case $sPrefix."change_param":
	    $oObject->ChangeParam();
	    break;
	    
	case $sPrefix."modification":
	    $oObject->Modification();
	    break;
	    
	case $sPrefix."group":
	    $oObject->Group();
	    break;
	    
	case $sPrefix."parts":
	    $oObject->Parts();
	    break;
	    
	case $sPrefix."vin":
	    $oObject->Vin();
	    break;
	    
	case $sPrefix."test":
	    $oObject->Test();
	    break;

	default:
		$oObject->Index();
		break;
}
?>