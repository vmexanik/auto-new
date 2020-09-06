{if $aAuthUser.type_=='manager'}
	{if $smarty.request.action=='finance_reestr_pko'}
		<input type=button class='at-btn' value="{$oLanguage->getMessage("order bill add")}"
		onclick="location.href='/?action=finance_bill_add&code_template=order_bill&return_action=finance_reestr_pko';">
	{elseif $smarty.request.action=='finance_reestr_bv'}
		<input type=button class='at-btn' value="{$oLanguage->getMessage("order bill bv add")}"
		onclick="location.href='/?action=finance_bill_add&code_template=order_bill_bv&return_action=finance_reestr_bv';">
	{elseif $smarty.request.action=='finance_reestr_rko'}
		<input type=button class='at-btn' value="{$oLanguage->getMessage("order bill rko add")}"
		onclick="location.href='/?action=finance_bill_add&code_template=order_bill_rko&return_action=finance_reestr_rko';">
	{/if}
{/if}