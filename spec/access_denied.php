<?php

$oObject=new AccessDenied();
$sPrefix='access_denied_';

switch (Base::$aRequest['action'])
{
	default:
		$oObject->Index();
		break;
}
?>