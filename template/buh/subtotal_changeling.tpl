{if $aAuthUser.type_=='manager'}
<tr>
	<td align=right> {$oLanguage->getMessage('Subtotal')}:</td>
	<td><b>{$dSubtotal.sum_amount_debit_start}</b>&nbsp;</td>
	<td><b>{$dSubtotal.sum_amount_credit_start}</b>&nbsp;</td>
	<td><b>{$dSubtotal.sum_amount_debit}</b>&nbsp;</td>
	<td><b>{$dSubtotal.sum_amount_credit}</b>&nbsp;</td>
	<td><b>{$dSubtotal.sum_amount_debit_end}</b>&nbsp;</td>
	<td><b>{$dSubtotal.sum_amount_credit_end}</b>&nbsp;</td>
</tr>
{/if}