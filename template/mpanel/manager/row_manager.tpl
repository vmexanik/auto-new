<td>{$aRow.id}</td>
<td>{$aRow.login}</td>
<td>{$aRow.name}</td>
<td>{$aRow.email}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>
{include file='addon/mpanel/yes_no.tpl' bData=$aRow.has_customer}
</td>
<td>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}

<a href="{strip}
		?action=user_change_password&id={$aRow.id}&call_action={$sBaseAction}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/copy.png"  hspace=3 align=absmiddle
		/>{$oLanguage->getDMessage('Change password')}</a>

</td>
