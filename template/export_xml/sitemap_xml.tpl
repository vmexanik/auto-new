<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	{$sBeforeContent}
	{foreach from=$aPrice item=aItem}
		<url>
			{strip}
			<loc>{$oLanguage->GetConstant('global:project_url')}/buy/{$aItem.cat_name}_{$aItem.code_}
				{$aExportXml.price_link_suffix}
			</loc>
			{/strip}
			<lastmod>{if $aItem.post_date && $aItem.post_date!=''}{$aItem.post_date|date_format:"%Y-%m-%d"}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}</lastmod>
			{if $aBuyTags.changefreq && $aBuyTags.priority}<changefreq>{$aBuyTags.changefreq}</changefreq>
			<priority>{$aBuyTags.priority}</priority>{/if}
		</url>
	{/foreach}
</urlset>



