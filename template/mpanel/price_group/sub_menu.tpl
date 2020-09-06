<a href="{strip}
		?action=price_group_remove_all_associate&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/forbidden.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('remove all associate')}</a>

{include file='addon/mpanel/base_sub_menu.tpl' sBaseAction=$sBaseAction}