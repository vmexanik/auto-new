<?php
$oObject = new ExportXml();
$sPrefix = $oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
	case $sPrefix.'gen':
		$oObject->Generate();
		break;

	default:
		$oObject->Index();
		break;
}
?>