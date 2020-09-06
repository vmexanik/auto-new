{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>

<a href="{strip}
		?action=price_group_check_associate&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/about.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('check associate')}</a>
		<br>

<a href="{strip}
		?action=price_group_update_associate&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/refresh.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('update associate')}</a>
		<br>
<a href="{strip}
		?action=price_group_remove_associate&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/forbidden.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('remove associate')}</a>
<br>
	{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='language'}<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
{elseif $sKey=='is_product_list_visible'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_product_list_visible}</td>
{elseif $sKey=='is_menu'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_menu}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}
