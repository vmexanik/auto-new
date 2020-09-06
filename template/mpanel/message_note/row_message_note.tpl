<td>{$aRow.id}</td>
<td>{$aRow.login}</td>
<td>{$aRow.reply_to}</td>
<td>{$aRow.name}</td>
<td>{$aRow.description}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_closed}</td>
<td>{$aRow.url}</td>
<td>{$aRow.post_date}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
