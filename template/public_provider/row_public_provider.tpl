{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='price_url'}
<td><a href="{$aRow.price_url}" target="_blank"
	>{$oLanguage->getMessage('download price')}</a>
</td>
{else}
<td>{$aRow.$sKey}</td>
{/if}
{/foreach}
