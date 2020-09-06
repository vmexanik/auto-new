<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:00
         compiled from manager_panel/manager_package_list/subtotal_order.tpl */ ?>
<tr>
	<td align=right><b><?php echo $this->_tpl_vars['dOrderCntTotal']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->getMessage('cnt.'); ?>
</b></td>
	<td colspan=5 align=right><nowrap><b><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['dOrderSubtotal']); ?>
</b></td>
	<td><nowrap><b><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['dOrderSubtotalProfit']); ?>
</b></td>
	<td colspan=3>&nbsp;</td>
</tr>