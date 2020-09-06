{foreach from=$aStaticItem item=aItem}
		<url>
			<loc>{$aItem.loc}</loc>
			<lastmod>{if $aItem.post_date && $aItem.post_date!=''}{$aItem.post_date|date_format:"%Y-%m-%d"}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}</lastmod>
			{if $aItem.changefreq && $aItem.priority}<changefreq>{$aItem.changefreq}</changefreq>
			<priority>{$aItem.priority}</priority>{/if}
		</url>
{/foreach}