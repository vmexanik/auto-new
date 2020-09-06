<?php

$oObject=new Comment();
$sPreffix='comment_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'post':
		$oObject->Post();
		break;

	case $sPreffix.'popup_post':
		$oObject->PopupPost();
		break;

	default:
		$oObject->Index();
		break;

}

?>