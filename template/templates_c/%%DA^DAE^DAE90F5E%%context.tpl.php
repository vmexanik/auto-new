<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from hint/context.tpl */ ?>

<div class="inline" onmouseover="$('#<?php echo $this->_tpl_vars['aContextHint']['key_']; ?>
<?php echo $this->_tpl_vars['sUnique']; ?>
').toggle();"
	onmouseout="$('#<?php echo $this->_tpl_vars['aContextHint']['key_']; ?>
<?php echo $this->_tpl_vars['sUnique']; ?>
').toggle();">&nbsp;<a href="javascript:;" class="ask"
	onclick="return false"><img src="/image/info.png" alt=""/></a>
<div style="text-align:left;display: none; font-weight:normal;"
	class="tip_div" id="<?php echo $this->_tpl_vars['aContextHint']['key_']; ?>
<?php echo $this->_tpl_vars['sUnique']; ?>
"><?php echo $this->_tpl_vars['aContextHint']['content']; ?>
</div>
</div>