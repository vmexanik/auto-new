<input class=at-btn type=button value='{$oLanguage->getMessage("Compose")}' onclick="change_form_action('message_form_id','message_compose');">
<select name='move_to_folder' style="width: 200px!important;height: 55px;">
	<option value=1>{$oLanguage->getMessage("Inbox")}</option>
	<option value=2>{$oLanguage->getMessage("Outbox")}</option>
	<option value=3>{$oLanguage->getMessage("Draft")}</option>
	<option value=4>{$oLanguage->getMessage("Archived")}</option>
</select>
<input class=at-btn type=button value='{$oLanguage->getMessage("Move")}' onclick="change_form_action('message_form_id','message_move_to_folder');">