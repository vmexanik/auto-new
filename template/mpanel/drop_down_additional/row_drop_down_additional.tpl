<td>{$aRow.id}</td>
<td>{$aRow.url}</td>
<td>{$aRow.title}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{*<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>*}
<td nowrap>{include file='addon/mpanel/base_row_action.tpl'
sBaseAction=$sBaseAction}</td>
