<?php

$oObject=new CarSelect();
$sPrefix='car_select_';

switch (Base::$aRequest['action'])
{
    default:
        $oObject->Index();
        break;
}
?>