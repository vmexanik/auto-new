<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

	<yml_catalog date="{$sCurrentDate}">

		<shop>

		    <name>{$oLanguage->GetConstant('export_xml:name','Autocity')}</name>
		    <company>{$oLanguage->GetConstant('export_xml:company','Autocity')}</company>
		    <url>{$oLanguage->GetConstant('global:project_url')}</url>

		    <currencies>
        		<currency id="UAH" rate="1"/>
   			</currencies>

			<categories>
				<category id="1">Автозапчасти</category>
			</categories>


		    <local_delivery_cost>30</local_delivery_cost>

		    <offers>