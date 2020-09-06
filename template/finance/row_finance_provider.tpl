<td>
	<div class="order-num">{$oLanguage->getMessage('#')}</div>
	{if $aRow.row_id}{$aRow.row_id}{elseif $aRow.id}{$aRow.id}{/if}
</td>
<td>
	<div class="order-num">{$oLanguage->getMessage('Date')}</div>
	{$aRow.post_date}
</td>
<td>
	<div class="order-num">{$oLanguage->getMessage('provider')}</div>
	{$oLanguage->AddOldParser('provider_uniq',$aRow.id_user)}<nobr><font color=green><b>{$oLanguage->PrintPrice($aRow.current_account_amount)}</b></font></nobr>
</td>
<td nowrap>
	<div class="order-num">{$oLanguage->getMessage('DebtAmount')}</div>
	{$oLanguage->PrintPrice($aRow.debt_amount)}
</td>
<td>
	{if $aRow.amount<0}
		<div class="order-num">{$oLanguage->getMessage('finance credit')}</div>
		{$oLanguage->PrintPrice($aRow.amount)}
	{/if}
</td>
<td>
	{if $aRow.amount>=0}
		<div class="order-num">{$oLanguage->getMessage('finance debet')}</div>
		{$oLanguage->PrintPrice($aRow.amount)}
	{/if}
</td>
<td nowrap>
	<div class="order-num">{$oLanguage->getMessage('AccountAmount')}</div>
	{$oLanguage->PrintPrice($aRow.account_amount)}
</td>
<td nowrap>
<div class="order-num">{$oLanguage->getMessage('Description')}</div>
{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b><br>{/if}
{$aRow.description}
{if $aRow.document}
	<br><span style="color:green">{$aRow.document}</span>
{/if}
{if $aRow.data!='prepay_provider' && $aRow.data!='debt_provider'}
<br><span style="color:green">Заказ № {$aRow.custom_id} ({$aRow.id_cart})</span>
{/if}

{if $aRow.debt_cart_unpaid}
	<font color=brown size="1">({$oLanguage->getMessage('cart debt')}:
		{$oLanguage->PrintPrice($aRow.debt_cart_unpaid)})</font>
{/if}
{if $aRow.package_status && $aRow.package_status=='refused'}
	<br>Заказ: {$oContent->GetOrderStatus($aRow.package_status)}
{/if}
{if $aRow.cart_status && $aRow.cart_status=='refused'}
	<br>Позиция: {$oContent->GetOrderStatus($aRow.cart_status)}
{/if}
{if !$aRow.package_status}
    <br><span style="color:red">Заказ не найден</span>
{/if}
</td>