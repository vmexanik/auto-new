<?php /* Smarty version 2.6.18, created on 2019-09-27 16:34:51
         compiled from message_input.tpl */ ?>
<?php $_from = $this->_tpl_vars['aMessageJavascript']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
	<input type="hidden" id="<?php echo $this->_tpl_vars['name']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
"/>
<?php endforeach; endif; unset($_from); ?>