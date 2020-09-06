{foreach item=aSignItem from=$aSign}
 <nobr><input type=checkbox name='{$aSignItem.code}' value=1 {if $aSignItem.default_check}checked{/if}
	onclick=" xajax_process_browse_url('?action=cart_package_update_sign&id={$aItem.id}&code={$aSignItem.code}&checked='+this.checked);"
	>{$aSignItem.name}<nobr>
{/foreach}