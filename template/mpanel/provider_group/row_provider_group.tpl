<td>{$aRow.id}</td>
<!--td>{$aRow.id_provider_group_type}</td-->
<td>{$aRow.code}</td>
<td>{$aRow.name}</td>
<td>{$aRow.group_margin}</td>
<!--td>{$aRow.group_discount}</td>
<td>{$aRow.group_term}</td-->
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>
{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction}
</td>
