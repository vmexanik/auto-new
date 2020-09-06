<?xml version='1.0' encoding='UTF-8'?>

<price date='{$sCurrentDate}'>
<name>{$oLanguage->GetConstant('export_xml:hotprice_project_name','www.autoklad.ua')}</name>
<url>{$oLanguage->GetConstant('global:project_url')}</url>
<currency code='USD' rate='{$oLanguage->GetConstant('export_xml:ava_currency_usd','8.05')}' />

<catalog>
	{foreach from=$aPriceGroup item=aItem}
		<category id='{$aItem.id}'>{$aItem.name}</category>
	{/foreach}
</catalog>

<items>
