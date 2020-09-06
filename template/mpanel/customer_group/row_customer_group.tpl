<td>{$aRow.id}</td>
<td>{$aRow.cg_name}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{$aRow.group_discount}</td>
<!--td>{$aRow.group_debt}</td>
<td>{$aRow.group_debt_percent}</td-->
<!--td>{$aRow.price_type}
{if $aRow.price_type=='margin'}
	{include file='addon/mpanel/yes_no.tpl' bData=$aRow.has_subcustomer}
{/if}
</td-->
<td>{$aRow.hours_expired_cart}</td>
<td>{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction not_delete=1}</td>