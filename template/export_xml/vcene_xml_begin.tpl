<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

	<yml_catalog date="{$sCurrentDate}">

		<shop>

		    <name>{$oLanguage->GetMessage('Autoklad')}</name>
		    <company>{$oLanguage->GetMessage('Company name')}</company>
		    <url>{$oLanguage->GetConstant('global:project_url')}</url>

		    <currencies>
        		<currency id="UAH" rate="1"/>
   			</currencies>

{if $aPriceGroup}
			<categories>
			{foreach from=$aPriceGroup item=aValue}
				<category id="{$aValue.id}">{$aValue.name}</category>
			{/foreach}
			</categories>
{/if}

		    <local_delivery_cost>30</local_delivery_cost>

		    <offers>
