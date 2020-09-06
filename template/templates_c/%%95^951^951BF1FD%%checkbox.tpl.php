<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from form/checkbox.tpl */ ?>
<input 
type=checkbox 
<?php if ($this->_tpl_vars['aInput']['name']): ?>name='<?php echo $this->_tpl_vars['aInput']['name']; ?>
'<?php endif; ?> 
value='<?php echo $this->_tpl_vars['aInput']['value']; ?>
'
<?php if ($this->_tpl_vars['aInput']['checked']): ?>checked<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['disabled']): ?> disabled <?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['class']): ?> class='<?php echo $this->_tpl_vars['aInput']['class']; ?>
'<?php else: ?> class="js-checkbox"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['onclick']): ?> onclick="<?php echo $this->_tpl_vars['aInput']['onclick']; ?>
"<?php endif; ?>
>