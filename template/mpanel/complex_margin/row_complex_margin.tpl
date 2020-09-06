{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction }
<nobr>
<A href="?action={$sBaseAction}_copy&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/documents.png"
	hspace=3 align=absmiddle>{$oLanguage->getDMessage('Copy')}</A>
</nobr>
</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{elseif $sKey=='is_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_brand}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}