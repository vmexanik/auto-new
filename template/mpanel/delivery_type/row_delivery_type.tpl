<td>{$aRow.id}</td>
<td>{$aRow.code}</td>
<td>{$aRow.name}</td>
<td><img src="{$aRow.image}"></td>
<td>{$aRow.price}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
