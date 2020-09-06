<td>{$aRow.id}</td>
<td>{$aRow.faq_category_name}</td>
<td>{$aRow.question}</td>
<td>{$aRow.answer}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td align="center">{$aRow.num}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
