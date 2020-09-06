<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.city}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.visible}</td>
<td>{$aRow.post_date}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}
</td>
