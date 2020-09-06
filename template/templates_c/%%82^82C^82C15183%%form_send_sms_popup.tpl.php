<?php /* Smarty version 2.6.18, created on 2019-09-27 16:53:32
         compiled from manager_panel/user_send_sms/form_send_sms_popup.tpl */ ?>
<div id="reg_error_popup"></div>
<form class="form-inline">
    <div class="col-sm-12">
		<label class="col-sm-3 col-form-label"><b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Phone Number'); ?>
:</b></label>
		<div class="col-sm-5">
	    	<input type="text" readonly class="form-control" id="user_phone" name=phone_number placeholder="(___)___ __ __" value=<?php echo $this->_tpl_vars['aUser']['phone']; ?>
>
		</div>
    </div>
    <br>
	<div class="col-sm-12" style=" padding-top: 5px;">
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=contacts'); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('contacts'); ?>
</button>
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=back_address'); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('back address'); ?>
</button>
	    <button type="button" class="btn btn-default"
	    	onclick="xajax_process_browse_url('/?action=manager_panel_user_send_sms_add_text&type=rekvisity'); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('rekvisity'); ?>
</button>
	</div>
    <br>
	<div class="col-sm-12" style=" padding-top: 5px;">
		<textarea style="height:auto;" class="form-control" id="text_sms" rows="6"></textarea>
	</div>
		<div class="col-sm-12" style=" padding-top: 5px;">
		<button type="button" class="btn btn-default" style="float:right;margin-right:10px;"
			onclick="$('.js_manager_panel_popup').hide();"><?php echo $this->_tpl_vars['oLanguage']->getMessage('close'); ?>
</button>		
		<button type="button" class="btn btn-primary" style="float:right;margin-right:10px;"
			onclick="<?php if (! $this->_tpl_vars['sms_available']): ?>alert('<?php echo $this->_tpl_vars['oLanguage']->getMessage('sms_not_activated'); ?>
');<?php else: ?>xajax_process_browse_url('/?action=manager_panel_user_send_sms_send&id_user=<?php echo $this->_tpl_vars['aUser']['id_user']; ?>
&sms='+encodeURIComponent($('#text_sms').val())); return false;<?php endif; ?>"><?php echo $this->_tpl_vars['oLanguage']->getMessage('send'); ?>
</button>
	</div>
</form>