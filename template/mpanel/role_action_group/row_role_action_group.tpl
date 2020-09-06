<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>
	{if $aRow.id != 1}
	<a onclick="javascript: xajax_process_browse_url('?action=role_action_group&id={$aRow.id}' );  return false;">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png"> Удалить
	</a>
	{/if}
</td>