<?xml version='1.0' encoding='UTF-8'?>
<price date='{$sCurrentDate}'>
<name>{$oLanguage->GetConstant('export_xml:ava_project_name','www.autoklad.ua')}</name>
<url>{$oLanguage->GetConstant('global:project_url')}</url>
<currency code='USD' rate='{$oLanguage->GetConstant('export_xml:ava_currency_usd','8.05')}' />
<region>{$oLanguage->GetConstant('export_xml:ava_region_name','Украина')}</region>

<catalog>
	{foreach from=$aPriceGroup item=aItem}
		<category id='{$aItem.id}'>{$aItem.name}</category>
	{/foreach}
</catalog>

<items>
