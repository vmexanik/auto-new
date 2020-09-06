<td style="text-align:right;">
	<div class="order-num">{$oLanguage->getMessage('num_str')}</div>
	{$aRow.num_str}
</td>
<td style="text-align:center;">
	<div class="order-num">{$oLanguage->getMessage('post_date')}</div>
	{$oLanguage->GetPostDate($aRow.post_date)}
</td>
<td style="text-align:right;">
	<div class="order-num">{$oLanguage->getMessage('DebtAmount')}</div>
	{$aRow.debt_amount}
</td>
<td style="text-align:right;">
	<div class="order-num">{$oLanguage->getMessage('finance credit')}</div>
	{$aRow.credit}
</td>
<td style="text-align:right;">
	<div class="order-num">{$oLanguage->getMessage('finance debet')}</div>
	{$aRow.debet}
</td>
<td style="text-align:right;">
	<div class="order-num">{$oLanguage->getMessage('AccountAmount')}</div>
	{$aRow.account_amount}
</td>
