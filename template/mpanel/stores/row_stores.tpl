<td>{$aRow.id}</td>
<td>{$aRow.code}</td>
<td>{$aRow.name}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_virtual}</td>
<td>{$aRow.provider}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_return}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_sale}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>

<td nowrap>
{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction}
</td>