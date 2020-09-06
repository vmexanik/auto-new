<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.city_name}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_featured}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>