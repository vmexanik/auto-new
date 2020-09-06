<?php /* Smarty version 2.6.18, created on 2019-10-07 10:44:38
         compiled from form/button.tpl */ ?>
<input 
type=button 
<?php if ($this->_tpl_vars['aInput']['class']): ?>class='<?php echo $this->_tpl_vars['aInput']['class']; ?>
'<?php endif; ?> 
<?php if ($this->_tpl_vars['aInput']['id']): ?>id='<?php echo $this->_tpl_vars['aInput']['id']; ?>
'<?php endif; ?>
value="<?php echo $this->_tpl_vars['oLanguage']->GetMessage($this->_tpl_vars['aInput']['value']); ?>
"				
<?php if ($this->_tpl_vars['aInput']['onclick']): ?>onclick="<?php echo ''; ?><?php echo $this->_tpl_vars['aInput']['onclick']; ?><?php echo ''; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['readonly']): ?>readonly='<?php echo $this->_tpl_vars['aInput']['readonly']; ?>
'<?php endif; ?> 
>