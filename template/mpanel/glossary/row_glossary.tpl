<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.first_letter}</td>
<!--td>{$aRow.term_action}</td-->
<td>{$aRow.title}</td>
<td>{$aRow.status}</td>
<td>{$aRow.description|strip_tags|truncate:80:""}</td>
<td>{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}</td>
