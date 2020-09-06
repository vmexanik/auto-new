{*<input type=button class='at-btn' value="{$oLanguage->getMessage("Export selected to Excel")}"
	onclick="mt.ChangeActionSubmit(document.getElementById('table_form'),'manager_export');">*}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Export all filtered to Excel")}"
	onclick="location.href='/?action=manager_export_all';">
{if $aAuthUser.is_super_manager}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Import statuses from Excel")}"
	onclick="location.href='/?action=manager_import_status';">
<br>
<br>
{*<select name='id_provider' id='id_provider' style='width: 75px;'>
	<option value='103'>EMEW</option>
</select>
<input type=button class='at-btn' value="{$oLanguage->getMessage("Export selected to Mega")}"
	onclick="mt.ChangeActionSubmit(this.form,'manager_export_mega');">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Export all filtered to Mega")}"
	onclick="mt.ChangeActionSubmit(this.form,'manager_export_mega_all');">*}
{/if}