<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:27
         compiled from manager_panel/manager_package_list/form_search_popup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manager_panel/manager_package_list/form_search_popup.tpl', 6, false),)), $this); ?>
<form>
	<div class="form-group row">
	    <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo $this->_tpl_vars['oLanguage']->getMessage('manager'); ?>
:</label>
	    <div class="col-sm-10">
	    	<select class="selectpicker" name="search[manager]" id="search_manager">
	      		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['aUserManager'],'selected' => $this->_tpl_vars['search_manager']), $this);?>

	      	</select>
	      	<img src="/image/design/refresh.png" style="padding-left:10px;cursor:pointer;" 
	      		onclick="$('#search_manager').val('');$('.selectpicker').selectpicker('refresh');">
	    </div>
  	</div>
  	<div class="form-group row">
	    <label for="colFormLabel" class="col-sm-2 col-form-label"><?php echo $this->_tpl_vars['oLanguage']->getMessage('phone'); ?>
:</label>
	    <div class="col-sm-9" style="width:250px;">
	    	<input class="form-control js-masked-input" type="text" placeholder="(___)___ __ __" 
	    		id="user_phone" name=search[phone] value="<?php echo $this->_tpl_vars['search_phone']; ?>
">
	    </div>
    	<img src="/image/design/refresh.png" style="padding-top: 6px;cursor:pointer;" onclick="$('#user_phone').val('');">
  	</div>
  	<button type="button" class="btn btn-default" style="float:right;"
  		onclick="xajax_process_browse_url('<?php echo '/?action=manager_panel_manager_package_list'; ?><?php if ($this->_tpl_vars['search_all_manager']): ?><?php echo '&search_all_manager=1'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['search_order_status']): ?><?php echo '&search_order_status='; ?><?php echo $this->_tpl_vars['search_order_status']; ?><?php echo ''; ?><?php endif; ?><?php echo '&is_popup=1&search[manager]=\'+$(\'#search_manager\').val()+\'&search[phone]=\'+$(\'#user_phone\').val()'; ?>
); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('find'); ?>
</button>
</form>