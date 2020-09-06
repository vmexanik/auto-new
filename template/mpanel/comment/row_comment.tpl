<td>{$aRow.id}</td>
<td><b>{$aRow.section}</b></td>
<td>{$aRow.ref_id}</td>
<td><b>{$aRow.name}</b>
<br>{$aRow.post_date}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>
<b>{$aRow.ip}</b><br>
{$aRow.content|truncate:100:""}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
