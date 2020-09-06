<!--input type=button class='at-btn' value="{$oLanguage->getMessage("Archive selected")}"
	onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to archive these items?")}'))
	 mt.ChangeActionSubmit(this.form,'cart_package_archive');"-->

<input type=button class='at-btn' value="{$oLanguage->getMessage("Pay for account")}"
	onclick="location.href='/?action=finance_payforaccount'">