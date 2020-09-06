<input type=button class='at-btn' value="{$oLanguage->getMessage("Check prefix")}" onclick="location.href='/?action=price_conformity'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear prefix")}" onclick="location.href='/?action=price_clear_pref'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear price")}" onclick="location.href='/?action=price_clear_import'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear providers ")}" onclick="location.href='/?action=price_clear_provider'">

<div style="padding-top:5px;">
<input type=button class='at-btn' style="background-color:#ec7e7e;" value="{$oLanguage->getMessage("delete checked items")}" onclick="if (confirm('{$oLanguage->getMessage("Are you sure delete checked items?")}')) mt.ChangeActionSubmit(this.form,'price_control_delete_items');">
<input type=button class='at-btn' style="background-color:#ec7e7e;" value="{$oLanguage->getMessage("delete filtered items")}" onclick="if (confirm('{$oLanguage->getMessage("Are you sure delete filtered items?")}')) mt.ChangeActionSubmit(this.form,'price_control_delete_filtered_items');">
<input type=button class='at-btn' style="background-color:#20ea43;" value="{$oLanguage->getMessage("add_to_price")}" onclick="if (confirm('{$oLanguage->getMessage("Are you sure add price items?")}')) mt.ChangeActionSubmit(this.form,'price_control_addtoprice_items');">
</div>

<input type=hidden id="return_action_buffer" name="return_action_buffer" value="{$smarty.server.REQUEST_URI|urlencode}">
