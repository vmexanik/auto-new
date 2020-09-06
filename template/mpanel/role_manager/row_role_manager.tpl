<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.description}</td>
<td>
    {if !$aRow.iassigned_permissions}
		<a onclick="javascript: xajax_process_browse_url('?action=role_manager&id={$aRow.id}' );  return false;">
		<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png"> Удалить
		</a>
    {/if}
</td>