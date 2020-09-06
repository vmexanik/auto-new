<td>
{if !$smarty.section.d.index}

	<font color=green>{$oLanguage->PrintPrice($aRow.current_account_amount)}</font>

	{if $aRow.current_debt_amount}
		{$oLanguage->getMessage(CustomerDebt)}: <a href='/?action=debt_log'>{$oLanguage->PrintPrice($aRow.current_debt_amount)}</a>
	{/if}

{/if}

{if $aRow.id_user!=$aAuthUser.id} - <b>{$aRow.login}</b>{/if}
</td>
<td nowrap>
{$oLanguage->PrintPrice($aRow.account_amount)}
/<font color=gray>{$oLanguage->PrintPrice($aRow.debt_amount)}</font>
</td>

<td>{if $aRow.amount>=0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>
<td>{if $aRow.amount<0}{$oLanguage->PrintPrice($aRow.amount)}{/if}</td>

<td>{$oLanguage->getDateTime($aRow.post)}</td>
<td>
{if $aRow.user_account_log_type_name}<font size="1" color=silver>{$aRow.user_account_log_type_name}</font><br>{/if}
{$aRow.description}</td>