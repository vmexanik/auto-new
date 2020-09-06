<?php

$oObject=new Sitemap();
$sPrefix='sitemap_';

switch (Base::$aRequest['action'])
{
	case $sPrefix.'update_links':
		$oObject->UpdateSitemapLinks();
		break;

	case $sPrefix.'gen':
		$oObject->Generate();
		break;

}
?>