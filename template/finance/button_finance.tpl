<input class="at-btn" value="{$oLanguage->GetMessage("Pay for account")}"
			onclick="location.href='/?action=finance_payforaccount'" type="button">

<input class="at-btn" value="{$oLanguage->GetMessage("Bills")}"
			onclick="location.href='/?action=finance_bill'" type="button">

<input type=button class='at-btn' value="{$oLanguage->getMessage("Export all filtered to Excel")}"
	onclick="mt.ChangeActionSubmit(this.form,'finance_export_all');">
<br><br>