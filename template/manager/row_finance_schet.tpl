<td>{$aRow.row_id}</td>
<td>{$aRow.post_date}</td>
<td>{$oLanguage->AddOldParser('provider_uniq',$aRow.id_user)}<nobr><font color=green><b>{$oLanguage->PrintPrice($aRow.current_account_amount)}</b></font></nobr></td>
<td nowrap>{$oLanguage->PrintPrice($aRow.debt_amount)}</td>
<td>{if $aRow.amount<0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>
<td>{if $aRow.amount>=0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>
<td nowrap>{$oLanguage->PrintPrice($aRow.account_amount)}</td>
<td>
{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b><br>{/if}
{$aRow.description}

{if $aRow.debt_cart_unpaid}
	<font color=brown size="1">({$oLanguage->getMessage('cart debt')}:
		{$oLanguage->PrintPrice($aRow.debt_cart_unpaid)})</font>
{/if}
</td>