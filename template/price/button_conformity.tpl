<input type=button class='at-btn' value="{$oLanguage->getMessage("auto generate pref")}" 
	onclick="location.href='/?action=price_conformity_auto&return={$sReturn|escape:"url"}';">
	
<input type=button class='at-btn' value="{$oLanguage->getMessage("auto assoc_cat")}" 
	onclick="location.href='/?action=price_auto_assoc_cat&return={$sReturn|escape:"url"}';">

<input type="button" class="at-btn" value="{$oLanguage->getMessage("clear preff")}" onclick="location.href='/?action=price_clear_pref'">