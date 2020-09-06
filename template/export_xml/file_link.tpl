{if $bShowSingleXml}
	<a href='/imgbank/xml/{$sSection}.{$sExt}' style="color: #50ACEC;">{$sSection}.{$sExt}</a>
{/if}

{if $aRange}
	{foreach from=$aRange item=aItem}
	<a href='/imgbank/xml/{$sSection}{$aItem}.{$sExt}' style="color: #50ACEC;">{$sSection}{$aItem}.{$sExt}</a>
	{/foreach}
{/if}
{if $aSitemap}
	{foreach from=$aSitemap item=aItem}
	<a href='/sitemap{$aItem}.{$sExt}' style="color: #50ACEC;">sitemap{$aItem}.{$sExt}</a>
	{/foreach}
{/if}
{* {if $aSitemapBefore}
	<a href='/{$sSection}.{$sExt}' style="color: #50ACEC;">{$sSection}.{$sExt}</a>
	{foreach from=$aSitemapBefore item=aItem}
	<a href='/sitemap-{$aItem}.{$sExt}' style="color: #50ACEC;">sitemap-{$aItem}.{$sExt}</a>
	{/foreach}
{/if} *}