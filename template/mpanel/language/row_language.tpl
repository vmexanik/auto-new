<td>{$aRow.id}</td>
<td>{$aRow.name} <br>
<font color=blue>{$aRow.domain}</font></td>
<td>{$aRow.code}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{if $aRow.image}<img src='{$aRow.image}' width='32'>{/if}</td>
<td>{$aRow.num}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
