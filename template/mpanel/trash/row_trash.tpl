<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.action}</td>
<td>{$aRow.id_element}</td>
<td>{$aRow.trashed_timestamp|date_format:"%d/%m/%Y %H:%M:%S"}</td>
<td>{$aRow.size}</td>
<td nowrap>
<A href="?action={$sBaseAction}_restore&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="if (confirm_delete_glg())
xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/reload_page.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Restore')}</A>

<A href="?action={$sBaseAction}_delete&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="if (confirm_delete_glg())
xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Delete')}</A>
</td>