<td>{$aRow.id}</td>
<td>{$aRow.login}</td>
<td>{$aRow.amount}</td>
<td>{$aRow.id_invoice}</td>
<td>{$aRow.code_template}</td>
<td nowrap>{$aRow.post|date_format:"%Y-%m-%d %H:%M:%S"}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
