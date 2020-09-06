<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from form/select.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'form/select.tpl', 12, false),)), $this); ?>
<?php if ($this->_tpl_vars['aInput']['checkbox']): ?><input type=checkbox name=search[method_is] value='1' <?php if ($_REQUEST['search']['method_is']): ?>checked<?php endif; ?>class="js-checkbox"><?php endif; ?>

<select 
name='<?php echo $this->_tpl_vars['aInput']['name']; ?>
' 
<?php if ($this->_tpl_vars['aInput']['class']): ?>class='<?php echo $this->_tpl_vars['aInput']['class']; ?>
'<?php else: ?>class="js-select"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['id']): ?>id='<?php echo $this->_tpl_vars['aInput']['id']; ?>
'<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['onchange']): ?>onChange="<?php echo ''; ?><?php echo $this->_tpl_vars['aInput']['onchange']; ?><?php echo ''; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['disabled']): ?> disabled <?php endif; ?>

<?php if ($this->_tpl_vars['aInput']['style']): ?>style='<?php echo $this->_tpl_vars['aInput']['style']; ?>
'<?php elseif ($this->_tpl_vars['aInput']['checkbox']): ?> style='width: 130px;'<?php endif; ?>
>
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['aInput']['options'],'selected' => $this->_tpl_vars['aInput']['selected']), $this);?>

</select>