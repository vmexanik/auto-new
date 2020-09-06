<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/template/balance.tpl */ ?>
<span style="color:<?php if ($this->_tpl_vars['aUser']['amount'] >= 0): ?>green<?php else: ?>red<?php endif; ?>;"><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aUser']['amount']); ?>
</span>