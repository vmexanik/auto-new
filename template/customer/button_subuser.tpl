<input type=button class='at-btn' value="{$oLanguage->getMessage("Add subuser")}"
	onclick="location.href='/?action=customer_subuser_add&second=1' ;">

{if !$aAuthUser.id_parent}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Add vip reprezentative")}"
	onclick="location.href='/?action=customer_subuser_add' ;">
{/if}

{if $bShowTestAddButton}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Add test subuser")}"
	onclick="location.href='/?action=customer_subuser_add&is_test=1&second=1' ;">
{/if}