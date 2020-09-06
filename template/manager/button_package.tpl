<input type=button class='at-btn' value="{$oLanguage->getMessage("Print package")}"
	onclick="mt.ChangeActionSubmit(this.form,'manager_package_print');">
<input type="hidden" name="return" value="{$sReturn|escape:"url"}" />

<input type=button class='at-btn' value="{$oLanguage->getMessage("Join orders")}"
	onclick="mt.ChangeActionSubmit(this.form,'manager_package_join_orders');">
<input type="hidden" name="return" value="{$sReturn|escape:"url"}" />