<?php

$sPrefix='customer_';
$oObject=new Customer();

switch (Base::$aRequest['action'])
{
	case $sPrefix.'profile':
		$oObject->Profile();
		break;

	case $sPrefix.'phone':
	case $sPrefix.'phone_edit':
		$oObject->Phone();
		break;

	case $sPrefix.'change_rating':
		$oObject->ChangeRating();
		break;

	default:
		$oObject->Index();
		break;

}
?>