<?php

$oObject=new Search();
$sPrefix='search_';
switch (Base::$aRequest['action'])
{
	default:
		$oObject->Index();
		break;
}
?>