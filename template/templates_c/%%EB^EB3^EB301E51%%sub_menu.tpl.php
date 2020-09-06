<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:34
         compiled from mpanel/price_group/sub_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mpanel/price_group/sub_menu.tpl', 2, false),)), $this); ?>
<a href="<?php echo '?action=price_group_remove_all_associate&id='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&return='; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?><?php echo ''; ?>
"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/forbidden.png"  hspace=3 align=absmiddle
		/><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('remove all associate'); ?>
</a>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_sub_menu.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>