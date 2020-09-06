<?php

$oObject=new PriceQueue();
$sPrefix=$oObject->sPrefix."_";

switch (Base::$aRequest['action'])
{
	case  $sPrefix.'get_mail_attachment':
		$oObject->GetMailAttachment();
		break;

	case  $sPrefix.'load_queue_price':
		$oObject->LoadQueuePrice();
		break;
	
	case  $sPrefix.'asunc_load_queue_price':
		$oObject->AsuncLoadQueuePrice();
		break;

	case  $sPrefix.'get_ftp_file':
		$oObject->GetFtpFile();
		break;	
	case  $sPrefix.'message_show':
		$oObject->LoadMessageLog();
		break;
	default:
		$oObject->Index();
		break;
}
?>