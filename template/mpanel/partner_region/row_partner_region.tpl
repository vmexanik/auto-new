<td>{$aRow.id}</td>
<td>{$aRow.language_name}</td>
<td>{$aRow.name}</td>
<!--td>{$aRow.name_ua}</td-->
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>