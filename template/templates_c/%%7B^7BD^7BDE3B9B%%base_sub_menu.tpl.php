<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from addon/mpanel/base_sub_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'addon/mpanel/base_sub_menu.tpl', 2, false),)), $this); ?>
<?php if ($this->_tpl_vars['not_add'] != 1): ?>
<a href="?action=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_add&amp;return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/new.png"/
	><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Add new'); ?>
</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['not_delete'] != 1 && ! $this->_tpl_vars['aAdmin']['is_base_denied']): ?>
<a href="?action=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_delete&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
	onclick="if (confirm_delete_glg())
	{
		update_input('main_form','action','<?php echo $this->_tpl_vars['sBaseAction']; ?>
_delete');
		update_input('main_form','return','<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
');
		submit_form();
	}   return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/delete.png"/
	><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Delete'); ?>
</a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/sub_menu_trash.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['is_trash'] == 1): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/sub_menu_trash.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['not_archive'] != 1): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/sub_menu_archive.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/sub_menu_unarchive.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>