<td width="50%">
<b>{$aRow.action_name}</b>
	<a onclick="javascript: xajax_process_browse_url('?action=role_permissions&mod=edit&id_role_action={$aRow.id}' );  return false;">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/edit.png"></a>
	<br />{$aRow.action_description}
	<div style="cursor:pointer;">{$oLanguage->getDMessage('set_exeption')}
		<a onclick="javascript: xajax_process_browse_url('?action=role_permissions&id_exeption={$aRow.id}' );  return false;">
		<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/add2.png">
		</a>
	</div>
</td>
<td width="25%">
	{foreach from=$aRow.assigned_roles item=value}
		<div>{$value.name} {if $value.id!=1} 
		<a onclick="javascript: xajax_process_browse_url('?action=role_permissions&id_permission={$value.id_permission}' );  return false;">
		<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png">
		</a>
		{/if}</div>
	{/foreach}
</td>
<td width="25%">
	{foreach from=$aRow.not_assigned_roles item=value}
		<div>{$value.name}
		<a onclick="javascript: xajax_process_browse_url('?action=role_permissions&id_role={$value.id}&id_action={$aRow.id}' );  return false;">
		<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/add2.png">
		</a>
		</div>
	{/foreach}
</td>