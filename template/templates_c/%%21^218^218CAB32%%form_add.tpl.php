<?php /* Smarty version 2.6.18, created on 2020-04-06 12:12:49
         compiled from mpanel/handbook/form_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mpanel/handbook/form_add.tpl', 35, false),)), $this); ?>
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Handbooks'); ?>
</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Name'); ?>
:<?php echo $this->_tpl_vars['sZir']; ?>
</td>
				<td><input type=text id=data[name] name=data[name] value="<?php echo $this->_tpl_vars['aData']['name']; ?>
"/></td>
			</tr>
			<tr>
				<td width="100%"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Table'); ?>
:<?php echo $this->_tpl_vars['sZir']; ?>
</td>
				<td><input type=text id=data[table_] name=data[table_] value="<?php echo $this->_tpl_vars['aData']['table_']; ?>
"/></td>
			</tr>
			<tr>
				<td width="100%"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Order number'); ?>
:<?php echo $this->_tpl_vars['sZir']; ?>
</td>
				<td><input type=text id=data[number] name=data[number] value="<?php echo $this->_tpl_vars['aData']['number']; ?>
"/></td>
			</tr>
			<tr>
				<td width="100%"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Collapsed'); ?>
:</td>
				<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/form_checkbox.tpl', 'smarty_include_vars' => array('sFieldName' => 'is_collapsed','bChecked' => $this->_tpl_vars['aData']['is_collapsed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
			</tr>
			
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aData']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/base_add_button.tpl', 'smarty_include_vars' => array('sBaseAction' => $this->_tpl_vars['sBaseAction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></FORM>