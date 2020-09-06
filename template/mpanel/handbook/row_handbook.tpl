<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.table_}</td>
<td>{$aRow.number}</td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_collapsed}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}</td>