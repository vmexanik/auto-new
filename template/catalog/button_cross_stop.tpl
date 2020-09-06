<input type=button class='at-btn' value="{$oLanguage->getMessage("Add Cross stop")}" 
	onclick="location.href='/?action=catalog_cross_stop_add&return={$sReturn|escape:"url"}';">
{*<input type=button class='at-btn' value="{$oLanguage->getMessage("import cross stop")}" 
	onclick="location.href='/?action=catalog_cross_stop_import&return={$sReturn|escape:"url"}';">*}
{if $smarty.session.manager.cross_stop_sql}
<input type=button class='at-btn' value="{$oLanguage->getMessage("delete filtered cross stop")}" 
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete filtered items?")}')) return false;
		location.href='/?action=catalog_cross_stop_filtered_delete';">
{/if}
