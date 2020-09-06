<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/template/right_order_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager_panel/template/right_order_menu.tpl', 15, false),)), $this); ?>
	<div class="dropdown" style="float:right;">
  		<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    		<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    		<span class="caret"></span>
  		</button>
	  	<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
		    <li>
		    	<a href="/?action=manager_panel_print_order&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
" onclick="xajax_process_browse_url(this.href); return false;">
		    		<img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order'); ?>
">
		    		<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order'); ?>

		    	</a>
		    </li>
		    <li role="separator" class="divider"></li>
		    <li>
		    	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_edit_order&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
'); return false;">
		    		<img src="/image/design/mp_edit.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('edit_order'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('edit_order'); ?>
">
		    		<?php echo $this->_tpl_vars['oLanguage']->getMessage('edit_order'); ?>

		    	</a>
		    </li>
		    <li <?php if ($this->_tpl_vars['sDisableSplitOrder']): ?>class="disabled"<?php endif; ?>>
		    	<a href="#" onclick="<?php if ($this->_tpl_vars['sDisableSplitOrder']): ?>alert('<?php echo $this->_tpl_vars['oLanguage']->getMessage('disable_split_order'); ?>
');"<?php else: ?>xajax_process_browse_url('/?action=manager_panel_edit_order_split&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
');<?php endif; ?> return false;">
		    		<img src="/image/design/unlink.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('split_order'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('split_order'); ?>
">
		    		<?php echo $this->_tpl_vars['oLanguage']->getMessage('split_order'); ?>

		    	</a>
		    </li>
		    <li>
		    	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
'); return false;">
		    		<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
		    		<?php echo $this->_tpl_vars['oLanguage']->getMessage('see_order'); ?>

		    	</a>
		    </li>
	  	</ul>
	</div>