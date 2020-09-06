<td>{$aRow.login}
<br><font color='grey'>{$oLanguage->GetDMessage('tm')}:{$aRow.manager_login}</font>
</td>
<td>{$aRow.id}</td>
<td>{$aRow.customer_name}</td>
<td>{$aRow.phone}</td>
<td>
{$aRow.customer_group_name}
</td>
<td>{$aRow.email}<br><font color='grey'>{$aRow.post_date}</font>
<br><font color='green'>{$aRow.post_date}</font> </td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.approved}</td>

<td nowrap>

{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}

<br>

<a href="{strip}
		?action=user_change_password&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/copy.png"  hspace=3 align=absmiddle
		/>{$oLanguage->getDMessage('Change password')}</a>
</td>