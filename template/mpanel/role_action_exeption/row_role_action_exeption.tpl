<td>{$aRow.id}</td>
<td>{$aRow.action_name}</td>
<td>
    {if $aRow.is_exeption}
	<font color=green><b>{$oLanguage->getDMessage('Yes')}</b></font>
{else}
	<font color=red><b>{$oLanguage->getDMessage('No')}</b></font>
{/if}
</td>
<td>
	{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction}
	<a onclick="javascript: xajax_process_browse_url('?action=role_action_exeption&id={$aRow.id}' );  return false;">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png"> Удалить
	</a>
</td>