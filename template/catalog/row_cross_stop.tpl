{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
    <a href="/?action=catalog_cross_stop_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
    ><img src="/image/edit.png" border=0 width=16 align=absmiddle title={$oLanguage->GetMessage('edit')} /></a>
    <br>
    <a href="/?action=catalog_cross_stop_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
    onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
    ><img src="/image/delete.png" border=0 width=16 align=absmiddle title={$oLanguage->GetMessage('delete')} /></a>
</td>
{elseif $sKey=='pref'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aPref[$aRow.pref]}
</td>
{elseif $sKey=='pref_crs'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aPref[$aRow.pref_crs]}
</td>
{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}