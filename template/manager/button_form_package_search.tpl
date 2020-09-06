{if $aAuthUser.type_=='manager'}
{*<input type=button class='at-btn' value="{$oLanguage->getMessage("Export excel")}" 
 onclick="mt.ChangeActionSubmit(this.form,'package_excel_export');">*}
<input type=button class='at-btn' value="{$oLanguage->getMessage("All to ebp")}" 
	onclick="location.href='/?action=ebp_cart_package_add&all=1&return={$sReturn|escape:"url"}';">
{/if}