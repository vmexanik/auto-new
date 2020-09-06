{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
    <a href="/?action=catalog_cross_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
    ><img src="/image/edit.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage('edit')}" title="{$oLanguage->getMessage('edit')}"/></a>
    <br>
    <a href="/?action=catalog_cross_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
    onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
    ><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage('del')}" title="{$oLanguage->getMessage('del')}"/></a>
</td>

{elseif $sKey=='manager_login'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.manager_login}
</td>
{elseif $sKey=='post_date'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {if $aRow.post_time}{$aRow.post_date|date_format:"%d.%m.%Y"}{else}-{/if}
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