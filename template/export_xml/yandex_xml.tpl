<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

	<yml_catalog date="{$sCurrentDate}">

		<shop>

		    <name>{$oLanguage->GetMessage('Autoklad')}</name>
		    <company>{$oLanguage->GetMessage('Company name')}</company>
		    <url>{$oLanguage->GetConstant('global:project_url')}</url>

		    <currencies>
        		<currency id="UAH" rate="1"/>
   			</currencies>

			<categories>
				<category id="1">{$oLanguage->GetMessage('Auto Parts')}</category>
			</categories>

		    <local_delivery_cost>30</local_delivery_cost>

		    <offers>
		    {foreach from=$aPrice item=aItem}
					<offer id="{$aItem.id}" available="true">
					    <url>{$oLanguage->GetConstant('global:project_url')}/?action=catalog_price_view&amp;code={$aItem.code_}</url>
					    <price>{$aItem.price}</price>
					    <currencyId>UAH</currencyId>
					    <categoryId>1</categoryId>
					    <picture></picture>
					    <delivery>true</delivery>
					    <name>{if $aItem.name_translate}{$aItem.name_translate}{else}{$aItem.name}{/if}</name>
					    <vendor>{$aItem.cat}</vendor>
					    <vendorCode>{$aItem.code_}</vendorCode>
					    <adult>false</adult>
					</offer>
				{/foreach}

		    </offers>

		</shop>

	</yml_catalog>
