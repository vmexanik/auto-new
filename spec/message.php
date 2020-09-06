<?php

$oObject=new Message();
$sPrefix='message_';

switch (Base::$aRequest['action'])
{
	case $sPrefix.'move_to_folder':
		$oObject->MoveToFolder();
		break;

	case $sPrefix.'reply':
		$oObject->Reply();
		break;

	case $sPrefix.'forward':
		$oObject->Forward();
		break;

	case $sPrefix.'send':
		$oObject->Send();
		break;

	case $sPrefix.'draft':
		$oObject->Draft();
		break;

	case $sPrefix.'compose':
		$oObject->Compose();
		break;

	case $sPrefix.'delete':
		$oObject->Delete();
		break;

	case $sPrefix.'clear':
		$oObject->Clear();
		break;

	case $sPrefix.'preview':
		$oObject->Preview();
		break;

	case $sPrefix.'change_current_folder':
		$oObject->ChangeCurrentFolder();
		break;

	case $sPrefix.'browse':
		$oObject->Browse();
		break;

	case $sPrefix.'note_close':
		$oObject->NoteClose();
		break;

	case $sPrefix.'preview_user_notification':
		$oObject->PreviewUserNotification();
		break;

	case $sPrefix.'send_bulk_user_notification':
		$oObject->SendBulkUserNotification();
		break;

	case $sPrefix.'change_starred':
		$oObject->ChangeStarred();
		break;

	case $sPrefix.'change_starred_message':
		$oObject->ChangeStarredMessage();
		break;

	default:
		$oObject->Browse();
		break;

}
?>