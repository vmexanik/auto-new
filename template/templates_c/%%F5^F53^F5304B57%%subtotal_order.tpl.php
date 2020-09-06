<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:10
         compiled from manager_panel/store/subtotal_order.tpl */ ?>
<tr>
	<td align=right><b><?php echo $this->_tpl_vars['dOrderCntTotal']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->getMessage('cnt.'); ?>
</b></td>
	<td colspan=4 align=right><nowrap><b><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['dOrderSubtotal']); ?>
</b></td>
	<td colspan=2></td>
</tr>