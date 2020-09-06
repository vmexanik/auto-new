<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:05
         compiled from manager/button_package.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager/button_package.tpl', 3, false),)), $this); ?>
<input type=button class='at-btn' value="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Print package'); ?>
"
	onclick="mt.ChangeActionSubmit(this.form,'manager_package_print');">
<input type="hidden" name="return" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" />

<input type=button class='at-btn' value="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Join orders'); ?>
"
	onclick="mt.ChangeActionSubmit(this.form,'manager_package_join_orders');">
<input type="hidden" name="return" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" />