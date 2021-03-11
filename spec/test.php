<?php

$oObject=new Test();
$sPreffix='test_';

switch (Base::$aRequest['action'])
{
    case $sPreffix."fix_cat":
        $oObject->FixCat();
        break;

    case $sPreffix.'vin_search':
        $oObject->VinSearch();
        break;
        
    case $sPreffix.'fm_images':
        $oObject->CheckImages();
        break;
        
    case $sPreffix.'fm_card':
        $oObject->CheckApp();
        break;
    
	default:
		$oObject->Index();
		break;

}
?>