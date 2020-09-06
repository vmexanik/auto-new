{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td><a href="/select/{$aRow.code_name}/"
	>{$aRow.name}</a>
</td>
{else}
<td>{$aRow.$sKey}</td>
{/if}
{/foreach}