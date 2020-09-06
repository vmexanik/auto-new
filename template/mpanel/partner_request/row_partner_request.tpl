<td>{$aRow.id}</td>
<td>{$aRow.region}
<br><b>{$aRow.email}</b>
</td>
<td>{$aRow.address}</td>
<td>{$aRow.phone} <br><b>{$aRow.login}</b></td>
<td>{if $aRow.storage==1}<font color="blue"><b>Yes</b></font>{else}<font color="red"><b>No</b></font>{/if}</td>
<td>{$aRow.manager_number}</td>
<td>{$aRow.post_date}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_called} {$aRow.call_comment}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
