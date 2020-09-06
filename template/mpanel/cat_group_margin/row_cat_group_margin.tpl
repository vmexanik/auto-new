<td><b>{$aRow.id}</b></td>
<td>{$aRow.name}</td>
<td align="right"><b>{$aRow.margin}%</b></td>
<td nowrap>
<nobr>
<A href="?action={$sBaseAction}_edit&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"  hspace=3 align=absmiddle>{$oLanguage->getDMessage('Edit')}</A>
</nobr>
</td>
