<td>{$oLanguage->AddOldParser('customer',$aRow.id_user)}

<nobr><font color=green><b>{$oLanguage->PrintPrice($aRow.current_account_amount)}</b></font></nobr></td>
<td nowrap>{$oLanguage->PrintPrice($aRow.account_amount)}
</td>
<td>{if $aRow.amount>=0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>
<td>{if $aRow.amount<0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>
<td>
{if $aRow.custom_id && in_array($aRow.id_user_account_log_type,array(1,8))}
	<a href='/?action=manager_package_list&search[id]={$aRow.custom_id}'>{$aRow.custom_id}
{/if}
</td>
<td>{$oLanguage->getDateTime($aRow.post)}</td>
<td>
{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b><br>{/if}
{$aRow.description}

{if $aRow.debt_cart_unpaid}
	<font color=brown size="1">({$oLanguage->getMessage('cart debt')}:
		{$oLanguage->PrintPrice($aRow.debt_cart_unpaid)})</font>
{/if}
</td>