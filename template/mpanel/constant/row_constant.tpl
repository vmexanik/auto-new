<td>{$aRow.id}</td>
<td>{$aRow.key_}</td>
<td>{$aRow.value|strip_tags|truncate:100}</td>
<td>{$aRow.description|strip_tags|truncate:100}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
