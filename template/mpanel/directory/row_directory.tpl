<td>{$aRow.id}</td>
<td>{$aRow.directory_category_name}</td>
<td>{$aRow.name}</td>
<td>{$aRow.description}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
