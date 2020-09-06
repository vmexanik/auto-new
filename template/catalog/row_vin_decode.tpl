{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>
    {if $aRow.virtual_title}
    <a href="?action=cat_sync_virtual&id={$aRow.id}" onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border="0" src="/libp/mpanel/images/small/refresh.png" hspace="3" align="absmiddle">Синхронизировать</a>
	<br>
	{/if}
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}
<nobr>
<A href="?action={$sBaseAction}_replace&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/replace2.png"
		hspace=3 align=absmiddle>{$oLanguage->getDMessage('Relink')}</A>
</nobr>
</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='image_tecdoc'}<td><img src='{if $aRow.image_tecdoc}{$sTecDocUrl}{$aRow.image_tecdoc}{/if}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='is_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_brand}</td>
{elseif $sKey=='is_vin_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_vin_brand}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}