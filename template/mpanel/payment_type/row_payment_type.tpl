<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.url}</td>
<td>{$aRow.description|strip_tags|truncate:150:" ..."}
<br>{$aRow.end_description|strip_tags|truncate:150:" ..."}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{$aRow.num}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
