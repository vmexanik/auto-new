<td>{$aRow.id}</td>
<td>{$aRow.id_price_group}<br><span style="color: grey;">[{$aRow.group_name}]</span></td>
<td>{$aRow.id_provider}<br><span style="color:{if $aRow.provider_visible && $aRow.prg_name!=null}green{else}grey{/if};">[{$aRow.provider_login}] {$aRow.provider_name}</span></td>
<td>{$aRow.code}</td>
<td>{$aRow.price}
{if $aRow.code_in && $aRow.code_in != $aRow.code}
<br><span style="color:green;">[{$aRow.code_in}]</span>
{/if}
</td>
<td>{$aRow.part_rus}</td>
<td>{$aRow.part_ua}</td>
<td><span style="color:{if $aRow.cat_visible}green{else}grey{/if}">{$aRow.pref}</span></td>
<td>{$aRow.cat}</td>
<td>{$aRow.post_date}</td>
<td>{$aRow.term}</td>
<td>{$aRow.stock}</td>
<td>{$aRow.number_min}</td>

<td nowrap>
{*include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction*}
<A href="?action={$sBaseAction}_delete&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="if (confirm_delete_glg())
xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png"
		hspace=3 align=absmiddle>{$oLanguage->getDMessage('Delete')}</A>
</nobr>
</td>