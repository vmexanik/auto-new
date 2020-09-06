<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from mpanel/handbook/row_handbook.tpl */ ?>
<td><?php echo $this->_tpl_vars['aRow']['id']; ?>
</td>
<td><?php echo $this->_tpl_vars['aRow']['name']; ?>
</td>
<td><?php echo $this->_tpl_vars['aRow']['table_']; ?>
</td>
<td><?php echo $this->_tpl_vars['aRow']['number']; ?>
</td>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_collapsed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td nowrap><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_row_action.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>