<table border=0 width=100%>
<tr align="left"><td>
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add image only")}" 
	onclick="location.href='/?action={$sBaseAction}_set_image&is_post=1'">
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add characteristic only")}" 
	onclick="location.href='/?action={$sBaseAction}_set_characteristic&is_post=1'">
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add cross only")}" 
	onclick="location.href='/?action={$sBaseAction}_set_cross&is_post=1'">
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add applicability only")}" 
	onclick="location.href='/?action={$sBaseAction}_set_applicability&is_post=1'">
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add All")}" 
	onclick="location.href='/?action={$sBaseAction}_set&is_post=1'">
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Delete all")}" 
	onclick="location.href='/?action={$sBaseAction}_delete&return={$sReturn|escape:"url"}'">
	<!--input type=button class='at-btn' value="{$oLanguage->getMessage("Delete last imported")}" 
	onclick="location.href='./?action=accessory_delete_import&imported=1&return={$sReturn|escape:"url"}'"-->
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Add Single")}" 
	onclick="location.href='/?action={$sBaseAction}_add&return={$sReturn|escape:"url"}'">

	</td>
</tr>
</table>