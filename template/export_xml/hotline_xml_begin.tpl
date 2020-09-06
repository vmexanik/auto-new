<?xml version="1.0" encoding="UTF-8"?>

<price>
	<date>{$smarty.now|date_format:"%Y-%m-%d %H:%M"}</date>
  	<firmName>{$oLanguage->GetConstant('export_xml:hotline_project_name','Интернет-магазин автозапчастей')}</firmName>
    <firmId>{$oLanguage->GetConstant('export_xml:hotline_id', 10595)}</firmId>
    <rate></rate>

	<categories>
		{foreach from=$aPriceGroup item=item key=key name=PriceGroup}
			<category>
		       <id>{$item.id}</id>
		       <name>{$item.name}</name>
		    </category>
		{/foreach}
	</categories>

	<items>
