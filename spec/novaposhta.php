<?php

$oObject=new Novaposhta();
$sPreffix='novaposhta';

switch (Base::$aRequest['action'])
{
    case $sPreffix.'_list':
        $oObject->GetAllList();
        break;

    case $sPreffix.'_delete_reestr':
        $oObject->DeleteReestr();
        break;
    
	default:
		$oObject->Index();
		break;

}
?>