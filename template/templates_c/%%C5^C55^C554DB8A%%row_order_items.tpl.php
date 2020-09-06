<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/manager_package_list/row_order_items.tpl */ ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'term'): ?>
<td> <?php echo $this->_tpl_vars['aRow']['term']; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'price'): ?>
<td><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price'],0,0,'<none>'); ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'price_total'): ?>
<td><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total'],0,0,'<none>'); ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'brand'): ?>
<td><?php if ($this->_tpl_vars['aRow']['cat_name_changed']): ?><?php echo $this->_tpl_vars['aRow']['cat_name_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['brand']; ?>
<?php endif; ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'code'): ?>
<td><?php if ($this->_tpl_vars['aRow']['code_changed']): ?><?php echo $this->_tpl_vars['aRow']['code_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['code']; ?>
<?php endif; ?></td>
<?php else: ?><td><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>