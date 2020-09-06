<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from addon/mpanel/yes_no.tpl */ ?>
<?php if ($this->_tpl_vars['bData']): ?>
	<font color=green><b><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Yes'); ?>
</b></font>
<?php else: ?>
	<font color=red><b><?php echo $this->_tpl_vars['oLanguage']->getDMessage('No'); ?>
</b></font>
<?php endif; ?>