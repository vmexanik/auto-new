<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:33
         compiled from manager/button_add_order_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager/button_add_order_item.tpl', 13, false),)), $this); ?>
<?php if ($this->_tpl_vars['aData']['order_status'] == 'pending' || $this->_tpl_vars['aData']['order_status'] == 'work'): ?>
<form action="/">
	<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
		<div class="at-user-details" style="box-shadow: none;">
		    <div class="header">
		        <?php echo $this->_tpl_vars['oLanguage']->GetMessage('add product to order'); ?>

		    </div>
		</div>
	
		
			<input type="hidden" name="action" value="manager_package_add_order_item">
			<input type="hidden" name="id_cart_package" value="<?php echo $this->_tpl_vars['aData']['id']; ?>
">
			<input type="hidden" name="return" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">
		
			<table>
				<tr>
					<td><div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('zzz_code'); ?>
</div></td>
					<td><input type="text" name="zzz_code" value=""></td>
				</tr>
				<tr>
					<td><div class="field-name"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('number'); ?>
</div></td>
					<td><input type="text" name="number" value="1" maxlength="3"></td>
				</tr>
			</table>
	</div>
	<div class="buttons">
		<input type="submit" class="at-btn" value="<?php echo $this->_tpl_vars['oLanguage']->GetMessage('add'); ?>
">
	</div>
</form>
<?php endif; ?>