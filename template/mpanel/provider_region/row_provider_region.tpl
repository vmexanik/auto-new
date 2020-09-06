<td>{$aRow.id}</td>
<!--td>{$aRow.code_delivery}</td-->
<td>{$aRow.code}</td>
<!--td>{$aRow.additional_delivery}</td>
<td>{$aRow.prw_name}</td-->
<td>{$aRow.name}</td>
<td>{$aRow.description}</td>
<!--td>${$aRow.delivery_cost}</td-->
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction not_delete=1}
</td>
