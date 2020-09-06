<?php /* Smarty version 2.6.18, created on 2020-04-05 19:56:04
         compiled from mpanel/rubricator/row_rubricator.tpl */ ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'action'): ?><td nowrap><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_row_action.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'image'): ?><td><img src='<?php echo $this->_tpl_vars['aRow']['image']; ?>
' align=left hspace=3 width=40></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'visible'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/visible.tpl', 'smarty_include_vars' => array('aRow' => $this->_tpl_vars['aRow'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'is_mainpage'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_mainpage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'is_menu_visible'): ?><td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_menu_visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php else: ?><td><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>