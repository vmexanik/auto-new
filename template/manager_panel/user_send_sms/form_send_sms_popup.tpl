<div id="reg_error_popup"></div>
<form class="form-inline">
    <div class="col-sm-12">
		<label class="col-sm-3 col-form-label"><b>{$oLanguage->getMessage('Phone Number')}:</b></label>
		<div class="col-sm-5">
	    	<input type="text" readonly class="form-control" id="user_phone" name=phone_number placeholder="(___)___ __ __" value={$aUser.phone}>
		</div>
    </div>
    <br>
	<div class="col-sm-12" style=" padding-top: 5px;">
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=contacts'); return false;">{$oLanguage->getMessage('contacts')}</button>
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=back_address'); return false;">{$oLanguage->getMessage('back address')}</button>
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=rekvisity'); return false;">{$oLanguage->getMessage('rekvisity')}</button>
	</div>
    <br>
	<div class="col-sm-12" style=" padding-top: 5px;">
		<textarea style="height:auto;" class="form-control" id="text_sms" rows="6"></textarea>
	</div>
	{*<div class="col-sm-12" style=" white-space: nowrap;">
		<label class="col-sm-3" style="font-weight:initial;margin-right:20px;">{$oLanguage->getMessage('length_sms')}:
			<b><span id="len_sms">0</span></b>
		</label>
		<label class="col-sm-3" style="font-weight:initial;margin-right:20px;">{$oLanguage->getMessage('left_symbol_sms')}:
			<b><span id="left_sms">160</span></b>
		</label>
		<label class="col-sm-3" style="font-weight:initial;margin-right:20px;">{$oLanguage->getMessage('size_sms')}:
			<b><span id="size_sms">1</span></b>
		</label>
	</div>
	*}
	<div class="col-sm-12" style=" padding-top: 5px;">
		<button type="button" class="btn btn-default" style="float:right;margin-right:10px;"
			onclick="$('.js_manager_panel_popup').hide();">{$oLanguage->getMessage('close')}</button>		
		<button type="button" class="btn btn-primary" style="float:right;margin-right:10px;"
			onclick="{if !$sms_available}alert('{$oLanguage->getMessage('sms_not_activated')}');{else}xajax_process_browse_url('/?action=manager_panel_user_send_sms_send&id_user={$aUser.id_user}&sms='+encodeURIComponent($('#text_sms').val())); return false;{/if}">{$oLanguage->getMessage('send')}</button>
	</div>
</form>