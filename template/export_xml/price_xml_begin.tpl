<?xml version="1.0" encoding="UTF-8"?>
<price date="{$sCurrentDate}">
<name>{$oLanguage->GetConstant('export_xml:price_project_name','Интернет-магазин автозапчастей')}</name>
{* <currency code="USD">{$oLanguage->GetConstant('export_xml:price_currency_usd','27.20')}</currency> *}

<catalog>
	{foreach from=$aPriceGroup item=aItem}
		<category id="{$aItem.id}" {if $aItem.id_parent>0}parentId="{$aItem.id_parent}"{/if}>{$aItem.name}</category>
	{/foreach}
</catalog>

<items>
