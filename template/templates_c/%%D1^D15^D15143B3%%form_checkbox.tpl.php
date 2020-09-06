<?php /* Smarty version 2.6.18, created on 2020-04-06 12:12:49
         compiled from addon/mpanel/form_checkbox.tpl */ ?>
<input type="hidden" name=data[<?php echo $this->_tpl_vars['sFieldName']; ?>
] value="0">
<input class="<?php echo $this->_tpl_vars['sClassCheckBox']; ?>
" type=checkbox name=data[<?php echo $this->_tpl_vars['sFieldName']; ?>
] value='1' style="width:22px;" <?php if ($this->_tpl_vars['bChecked']): ?>checked<?php endif; ?>
	<?php if ($this->_tpl_vars['sOnClick']): ?>onClick="<?php echo $this->_tpl_vars['sOnClick']; ?>
"<?php endif; ?>>