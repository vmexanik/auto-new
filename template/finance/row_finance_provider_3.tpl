<td style="text-align:right;">&nbsp;</td>
<td style="text-align:left;background-color: lightgrey;font-weight: bold;">
	<div class="order-num" style="color:#F3F3F3">{$oLanguage->getMessage('provider')}</div>
	{$aRow.name}
</td>
<td style="text-align:right;background-color: lightgrey;font-weight: bold;">&nbsp;</td>
<td style="text-align:right;background-color: lightgrey;font-weight: bold;">
	<div class="order-num" style="color:#F3F3F3">{$oLanguage->getMessage('DebtAmount')}</div>
	{$aRow.start}
</td>
<td style="text-align:right;background-color: lightgrey;font-weight: bold;">
	<div class="order-num" style="color:#F3F3F3">{$oLanguage->getMessage('finance credit')}</div>
	{$aRow.credit}
</td>
<td style="text-align:right;background-color: lightgrey;font-weight: bold;">
	<div class="order-num" style="color:#F3F3F3">{$oLanguage->getMessage('finance debet')}</div>
	{$aRow.debet}
</td>
<td style="text-align:right;background-color: lightgrey;font-weight: bold;">
	<div class="order-num" style="color:#F3F3F3">{$oLanguage->getMessage('AccountAmount')}</div>
	{$aRow.end}
</td>
{if $aRow.items}
	</tr>
	<tr>
	{foreach key=key item=aValue from=$aRow.items}
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('num_str')}</div>
			{$aValue.num_str}
		</td>
		<td style="text-align:left;">{*$aValue.document*}</td>
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('post_date')}</div>
			{$oLanguage->GetPostDate($aValue.post_date)}
		</td>
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('DebtAmount')}</div>
			{$aValue.debt_amount}
		</td>
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('finance credit')}</div>
			{$aValue.credit}
		</td>
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('finance debet')}</div>
			{$aValue.debet}
		</td>
		<td style="text-align:right;">
			<div class="order-num">{$oLanguage->getMessage('AccountAmount')}</div>
			{$aValue.account_amount}
		</td>
		</tr><tr>
	{/foreach}
{/if}