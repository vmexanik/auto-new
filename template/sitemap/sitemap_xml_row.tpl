{foreach from=$aSitemap item=aItem}
	<url><loc>{$sServer}{$aItem.url}</loc></url>
{/foreach}
