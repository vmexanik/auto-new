<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:34
         compiled from addon/mpanel/visible.tpl */ ?>
<?php if ($this->_tpl_vars['aRow']['visible']): ?>
	<font color=green><b><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Visible'); ?>
</b></font>
<?php else: ?>
	<font color=red><b><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Invisible'); ?>
</b></font>
<?php endif; ?>