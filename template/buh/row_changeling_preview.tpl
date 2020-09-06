{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td>
{if $aAuthUser.type_=='manager'}
	{if $aRow.post_date|date_format:"%Y-%m-%d">=$sDateFrom and $aRow.post_date|date_format:"%Y-%m-%d"<=$sDateTo}
	<a href="/?action=buh_edit_amount&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0  width=16 align=absmiddle 
	alt="{$oLanguage->getMessage("Edit")}" title="{$oLanguage->getMessage("Edit")}" /></a>
	{/if}
{/if}
</td>
{else}
	{if $sKey=='document'}
		<td>{$oLanguage->getMessage($aRow.document)} - {$aRow.id_document}</td>
	{else}
		<td>{$aRow.$sKey}</td>
	{/if}
{/if}
{/foreach}