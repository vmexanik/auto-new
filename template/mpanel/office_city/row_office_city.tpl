<td>{$aRow.id}</td>
<td>{$aRow.office_country_name}</td>
<td>{$aRow.office_region_name}</td>
<td>{$aRow.name}</td>
<td>{$aRow.code}</td>
<td>{$aRow.term_delivery}</td>
<td>{$aRow.markup}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}
</td>