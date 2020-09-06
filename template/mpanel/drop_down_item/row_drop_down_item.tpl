<td nowrap>{include file='addon/mpanel/drop_down/id.tpl'}</td>
<td>{$aRow.name}</td>
<td>{$aRow.code}</td>
<td>{$aRow.num}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
{if $oLanguage->GetConstant('user_role:is_available')}
<td>
	{$oADropDownItem->GetRoleCheckbox($aRow.id)}
</td>
{/if}
<td>
<nobr>
<A href="?action={$sBaseAction}_edit&id={$aRow.id}&amp;id_parent={$aRequest.id_parent}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"  hspace=3 align=absmiddle
	>{$oLanguage->getDMessage('Edit')}</A>
</nobr>

<nobr>
<A href="?action={$sBaseAction}_delete&id={$aRow.id}&amp;id_parent={$aRequest.id_parent}&return={$sReturn|escape:"url"}"
	onclick="if (confirm_delete_glg())
	xajax_process_browse_url(this.href);  return false;">
	<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png" hspace=3 align=absmiddle
	>{$oLanguage->getDMessage('Delete')}</A></nobr>
</td>
