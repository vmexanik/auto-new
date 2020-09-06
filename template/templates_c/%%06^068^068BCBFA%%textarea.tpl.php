<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:33
         compiled from form/textarea.tpl */ ?>
<textarea 
name='<?php echo $this->_tpl_vars['aInput']['name']; ?>
'
<?php if ($this->_tpl_vars['aInput']['disabled']): ?> disabled <?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['class']): ?>class='<?php echo $this->_tpl_vars['aInput']['class']; ?>
'<?php endif; ?> 
<?php if ($this->_tpl_vars['aInput']['style']): ?>style='<?php echo $this->_tpl_vars['aInput']['style']; ?>
'<?php endif; ?> 
<?php if ($this->_tpl_vars['aInput']['cols']): ?>cols='<?php echo $this->_tpl_vars['aInput']['cols']; ?>
'<?php endif; ?> 
<?php if ($this->_tpl_vars['aInput']['rows']): ?>rows='<?php echo $this->_tpl_vars['aInput']['rows']; ?>
'<?php endif; ?> 
>
<?php if ($this->_tpl_vars['aInput']['value']): ?><?php echo $this->_tpl_vars['aInput']['value']; ?>
<?php endif; ?>
</textarea>