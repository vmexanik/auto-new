<?php

require_once(SERVER_PATH.'/class/core/PrintContent.php'); ;
$oPrintContent=new PrintContent();
//$sPreffix='print_content_';

switch (Base::$aRequest['action'])
{
	default:
		$oPrintContent->Index();
		break;

}
?>