<tr>
	<td colspan=4 align=right>
		<div class="order-num">&nbsp;</div>
		{$oLanguage->getMessage('Subtotal')}:
	</td>
	<td>
		<div class="order-num">{$oLanguage->getMessage('finance credit')}</div>
		<b>{$oLanguage->PrintPrice($dTotalAmountCredit)}</b>
	</td>
	<td>
		<div class="order-num">{$oLanguage->getMessage('finance debet')}</div>
		<b>{$oLanguage->PrintPrice($dTotalAmountDebet)}</b>
	</td>
	<td colspan=2>&nbsp;</td>
</tr>
