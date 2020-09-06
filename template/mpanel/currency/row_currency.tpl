<td>{$aRow.id}</td>
<td>{$aRow.code}</td>
<td>{$aRow.name}</td>
<td>{$aRow.symbol}</td>
<td>{include file='addon/mpanel/image.tpl' aRow=$aRow sWidth=30}</td>
<td>{$aRow.value}</td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{$aRow.num}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td align=center>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}
<br />
<nobr>
<A href="?action=log_finance_search&search[section]=change_currency&search[currency_code]={$aRow.code}&return=action=log_finance"
	onclick="xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/notebook.png" hspace=3 align=absmiddle
	>{$oLanguage->getDMessage('View change log')}</A></nobr>
</td>
