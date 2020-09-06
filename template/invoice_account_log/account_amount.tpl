<br>
<table class="datatable">
<tr>
	<th>{$oLanguage->GetMessage('IAccountAmount')}</th>
	<th>{$oLanguage->GetMessage('ICartInWork')}</th>
	<th>{$oLanguage->GetMessage('IUninvoiced Amount')}</th>
	<th>{$oLanguage->GetMessage('IDebt Amount')}{$s50Hint}</th>
	<th></th>
	<th>{$oLanguage->GetMessage('IInvoiceAmount')}</th>
</tr>
<tr class="none">
	<td>{$oLanguage->PrintPrice($aAuthUser.amount)}</td>
	<td>{$oLanguage->PrintPrice($aInvoiceTemplate.cart_in_work)}</td>
	<td>{$oLanguage->PrintPrice($aInvoiceTemplate.uninvoiced_amount,true)}</td>
	<td>-{$oLanguage->PrintPrice($aInvoiceTemplate.debt_amount)}</td>
	<td>=</td>
	<td>{$oLanguage->PrintPrice($aInvoiceTemplate.invoice_amount)}</td>
</tr>
</table>
<br>