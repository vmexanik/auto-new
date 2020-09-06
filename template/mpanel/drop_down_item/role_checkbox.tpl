 <nobr><input type=checkbox name=data[] value='1' style="width:22px;" {if $bChecked}checked{/if}
	onchange=" {strip}xajax_process_browse_url('?action=drop_down_item_role_update&id_drop_down={$iIdDropDown}
		&id_user_role={$iIdUserRole}&checked='+this.checked);{/strip}"
	/>{$sRoleName}</nobr>