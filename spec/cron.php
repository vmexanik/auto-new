<?php

$sPreffix='cron_';

switch (Base::$aRequest['action'])
{
	case $sPreffix.'minutely':
		Mail::SendDelayed(3);
		//Cron::UpdatePriceMargin(); - deprecated
		//		Sms::SendDelayed();
		break;
		//-----------------------------------------------------------------------------------------------
	case $sPreffix.'minutely2':
		ElitRoma::CronSecond();
		break;

	case $sPreffix.'minutely_10':
		$oPriceQueue= new PriceQueue();
		$oPriceQueue->LoadQueuePrice();
		break;
		//-----------------------------------------------------------------------------------------------

	case $sPreffix.'hourly':
		Message::SendBulkUserNotification();
		Cron::DeleteTemporaryCustomer();
		//--------------------------------------------
		$oPriceQueue= new PriceQueue();
		$oPriceQueue->GetFtpFile();
		//--------------------------------------------
		Cron::MoveExpiredCart();
		break;
		//-----------------------------------------------------------------------------------------------


	case $sPreffix.'daily':
		Cron::AssociateDelayedPricesMinutely();
		Discount::Refresh();

		Cron::ClearOldData();
// 		Cron::SendDbBackup();
		//--------------------------------------------
		$oPrice= new Price();
		$oPrice->ClearOldQueueFiles();
		//--------------------------------------------
		Cron::ClearAllOld();
		// Cron::CheckProviderLogAccountAmount(); - if need analize/fixed balance provider error
		break;
		//-----------------------------------------------------------------------------------------------
	case $sPreffix.'weekly':
		//--------------------------------------------
		$oPrice= new Price();
		$oPrice->ClearOldQueueImportRecords();
		break;
		//-----------------------------------------------------------------------------------------------

	case $sPreffix.'monthly':
		//--------------------------------------------
		break;
		//-----------------------------------------------------------------------------------------------

	case $sPreffix.'get_mail':
		$oPriceQueue= new PriceQueue();
		$oPriceQueue->GetMailAttachment();
		break;
		//-----------------------------------------------------------------------------------------------
		
	case $sPreffix.'generate_sitemap':
		$url=Base::GetConstant('global:project_url');
		$params=array('action' => 'cron_async_generate_sitemap','is_post' => 1);
		PriceQueue::SendRequest($url, $params);
		break;
	//-----------------------------------------------------------------------------------------------
	case $sPreffix.'async_generate_sitemap':
		$oSitemap=new Sitemap();
		$oSitemap->Generate();
		break;
	//-----------------------------------------------------------------------------------------------

	default:
		break;
		//-----------------------------------------------------------------------------------------------
}

die(date("Y-m-d H:i:s").': Ok');
?>