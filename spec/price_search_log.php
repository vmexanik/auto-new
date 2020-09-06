<?php
$oObject = new PriceSearchLog();
$sPrefix = 'price_search_log_';

switch (Base::$aRequest['action'])
{
	default:
		$oObject->Index();
		break;
}
?>