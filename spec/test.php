<?php

$oObject=new Test();
$sPreffix='test_';

switch (Base::$aRequest['action'])
{
    case $sPreffix.'vin_search':
        $oObject->VinSearch();
        break;
    
	default:
		$oObject->Index();
		break;

}
?>