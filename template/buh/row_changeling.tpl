{if $aAuthUser.type_=='manager'}
<td>{$aRow.name_subconto1}</td>
{/if}
<td>{$aRow.amount_debit_start}</td>
<td>{$aRow.amount_credit_start}</td>
<td>{if $aRow.amount_debit>0}<a href="/?action=buh_changeling_preview
	&search[id_buh]={$aRow.id_buh}&search[id_subconto1]={$aRow.id_buh_subconto1}
	&search[date_from]={$smarty.request.search.date_from}&search[date_to]={$smarty.request.search.date_to}
	&return={$sReturn|escape:"url"}">{/if}
	{$aRow.amount_debit}
</td>
<td>{if $aRow.amount_credit>0}<a href="/?action=buh_changeling_preview
	&search[id_buh]={$aRow.id_buh}&search[id_subconto1]={$aRow.id_buh_subconto1}
	&search[date_from]={$smarty.request.search.date_from}&search[date_to]={$smarty.request.search.date_to}
	&return={$sReturn|escape:"url"}">{/if}
	{$aRow.amount_credit}</td>
<td>{$aRow.amount_debit_end}</td>
<td>{$aRow.amount_credit_end}</td>