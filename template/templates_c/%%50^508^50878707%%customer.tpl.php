<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:06
         compiled from hint/customer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'hint/customer.tpl', 45, false),)), $this); ?>
<?php if ($this->_tpl_vars['aData']['price_type'] == 'margin'): ?>
	<?php $this->assign('sLoginColor', 'brown'); ?>
<?php else: ?>
	<?php $this->assign('sLoginColor', 'gray'); ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['aData']['vip']): ?>
	<?php $this->assign('sLoginColor', 'red'); ?>
<?php endif; ?>
<span onmouseover="$('#<?php if ($this->_tpl_vars['aData']['login_strip']): ?><?php echo $this->_tpl_vars['aData']['login_strip']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aData']['login']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['aData']['id']; ?>
').toggle();"
	onmouseout="$('#<?php if ($this->_tpl_vars['aData']['login_strip']): ?><?php echo $this->_tpl_vars['aData']['login_strip']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aData']['login']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['aData']['id']; ?>
').toggle();"><a href="#"
	onclick="return false"
	style=" color: <?php echo $this->_tpl_vars['sLoginColor']; ?>
;"
	><?php echo $this->_tpl_vars['aData']['login']; ?>
<?php if ($this->_tpl_vars['aData']['name']): ?> - <?php echo $this->_tpl_vars['aData']['name']; ?>
<?php endif; ?><?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager' && $this->_tpl_vars['aData']['manager_login']): ?>
<br>(<?php echo $this->_tpl_vars['aData']['manager_login']; ?>
)
<?php endif; ?></a> <?php if ($this->_tpl_vars['aData']['login_parent']): ?><font color=green><b><?php echo $this->_tpl_vars['aData']['login_parent']; ?>
</b></font><?php endif; ?>
<div align=left style="display: none; width: 350px;" class="tip_div" id="<?php if ($this->_tpl_vars['aData']['login_strip']): ?><?php echo $this->_tpl_vars['aData']['login_strip']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aData']['login']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['aData']['id']; ?>
">
	<p><b><font color="<?php echo $this->_tpl_vars['sLoginColor']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->getMessage('Login'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['login']; ?>
</font>
	<?php if ($this->_tpl_vars['aData']['login_parent']): ?><?php echo $this->_tpl_vars['oLanguage']->getMessage('LoginParent'); ?>
:
	<font color=green><b><?php echo $this->_tpl_vars['aData']['login_parent']; ?>
</b></font><?php endif; ?>
	<a href='/?action=message_compose&compose_to=<?php echo $this->_tpl_vars['aData']['login']; ?>
'
		><?php echo $this->_tpl_vars['oLanguage']->getMessage('Send message to customer'); ?>
</a>
	<br>
	<?php if ($this->_tpl_vars['aData']['password_temp']): ?>
	<b><font color=red><?php echo $this->_tpl_vars['oLanguage']->getMessage('Password'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['password_temp']; ?>
</font><br>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['aData']['customer_name']): ?>
		<?php $this->assign('sCustomerName', $this->_tpl_vars['aData']['customer_name']); ?>
	<?php else: ?>
		<?php $this->assign('sCustomerName', $this->_tpl_vars['aData']['name']); ?>
	<?php endif; ?>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Group'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['customer_group_name']; ?>
<br>
		<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('custamount'); ?>
:</b> <span style="font-size:120%; color: <?php if ($this->_tpl_vars['aData']['amount'] > 0): ?>green<?php else: ?>red<?php endif; ?>;"><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aData']['amount']); ?>
</span><br>

	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Email'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['email']; ?>
<br>
	<?php if ($this->_tpl_vars['aData']['id_user_customer_type'] == '1'): ?><b><?php echo $this->_tpl_vars['oLanguage']->getMessage('User customer typ'); ?>
:</b> <?php echo $this->_tpl_vars['oLanguage']->getMessage("частное лицо"); ?>
<br><?php endif; ?>
	<?php if ($this->_tpl_vars['aData']['id_user_customer_type'] == '2'): ?><b><?php echo $this->_tpl_vars['oLanguage']->getMessage('User customer typ'); ?>
:</b> <?php echo $this->_tpl_vars['oLanguage']->getMessage("юридическое лицо"); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Entity name'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['entity_type'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['entity_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('additional_field1'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['additional_field1'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('additional_field2'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['additional_field2'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('additional_field3'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['additional_field3'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('additional_field4'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['additional_field4'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('additional_field5'); ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['additional_field5'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<br>
	<?php endif; ?>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('FLName Delivery'); ?>
:</b> <?php echo $this->_tpl_vars['sCustomerName']; ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('City'); ?>
:</b> <font color=blue><?php echo $this->_tpl_vars['aData']['city']; ?>
 / <?php echo $this->_tpl_vars['aData']['delivery_type_name']; ?>
</font><br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Address'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['address']; ?>
<br>
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Phone'); ?>
:</b> <?php echo $this->_tpl_vars['aData']['phone']; ?>
 <?php echo $this->_tpl_vars['aData']['phone2']; ?>
 <?php echo $this->_tpl_vars['aData']['phone3']; ?>
<br>
	
</div>
</span>