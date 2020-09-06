<td>{$aRow.id}</td>
<td>{$aRow.name}</td>
<td>{$aRow.zzz_code}</td>
<td>{$aRow.old_price}</td>
<td>{include file='addon/mpanel/image.tpl' aRow=$aRow sWidth=30}</td>
<td>{if $aRow.bage}{$oLanguage->getDMessage($aRow.bage)}{/if}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
</td>
