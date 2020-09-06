<input type=button class='at-btn' style="margin-left: -5px;" value="{$oLanguage->getMessage("Add Cross")}" 
	onclick="location.href='/?action=catalog_cross_add&return={$sReturn|escape:"url"}';">
<input type=button class='at-btn' value="{$oLanguage->getMessage("import cross")}" 
	onclick="location.href='/?action=catalog_cross_import&return={$sReturn|escape:"url"}';">
{if $smarty.session.manager.cross_sql}
<input type=button class='at-btn' value="{$oLanguage->getMessage("delete filtered cross")}" 
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete filtered items?")}')) return false;
		location.href='/?action=catalog_cross_filtered_delete';">
{/if}
