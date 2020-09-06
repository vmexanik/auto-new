<?php

require_once(SERVER_PATH.'/class/module/News.php'); ;
$oNews=new News();
$sPreffix='news_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'preview':
		$oNews->Preview();
		break;

	default:
		$oNews->Index();
		break;

}
?>