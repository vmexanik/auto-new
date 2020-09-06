<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:34
         compiled from mpanel/price_group/row_price_group.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mpanel/price_group/row_price_group.tpl', 5, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'action'): ?><td nowrap>

<a href="<?php echo '?action=price_group_check_associate&id='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&return='; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?><?php echo ''; ?>
"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/about.png"  hspace=3 align=absmiddle
		/><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('check associate'); ?>
</a>
		<br>

<a href="<?php echo '?action=price_group_update_associate&id='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&return='; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?><?php echo ''; ?>
"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/refresh.png"  hspace=3 align=absmiddle
		/><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('update associate'); ?>
</a>
		<br>
<a href="<?php echo '?action=price_group_remove_associate&id='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&return='; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?><?php echo ''; ?>
"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/forbidden.png"  hspace=3 align=absmiddle
		/><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('remove associate'); ?>
</a>
<br>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_row_action.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'image'): ?><td><img src='<?php echo $this->_tpl_vars['aRow']['image']; ?>
' align=left hspace=3 width=40></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'visible'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/visible.tpl', 'smarty_include_vars' => array('aRow' => $this->_tpl_vars['aRow'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'language'): ?><td nowrap><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_lang_select.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'is_product_list_visible'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_product_list_visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'is_menu'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'is_main'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_main'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php else: ?><td><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>