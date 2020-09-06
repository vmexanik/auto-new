{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
	<a href="/?action=manager_cat_pref_add&id={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Edit")}" 
		  title="{$oLanguage->getMessage("Edit")}" /></a>
    <br>
	<a href="/?action=manager_cat_pref_delete&id={$aRow.id}"
	   onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
		><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Delete")}" 
		  title="{$oLanguage->getMessage("Delete")}" /></a>
</td>
{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}