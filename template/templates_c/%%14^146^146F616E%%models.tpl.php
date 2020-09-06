<?php /* Smarty version 2.6.18, created on 2020-04-05 17:45:56
         compiled from car_select/models.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'car_select/models.tpl', 1, false),)), $this); ?>
<select class="js-select js-select-model" onchange="send_param(this.options[this.selectedIndex].value,'models','<?php echo ((is_array($_tmp=$this->_tpl_vars['aModel']['short_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'); return false;">
<option value="">Выберите модель</option>
<?php $_from = $this->_tpl_vars['aCarSelectModelGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aCarSelectModel']):
?>
<?php $_from = $this->_tpl_vars['aCarSelectModel']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aModel']):
?>
<option value="/?action=car_select<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'car_select/xajax_link.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&year=<?php echo $this->_tpl_vars['sCarSelectedYear']; ?>
&car_select[brand]=<?php echo $this->_tpl_vars['sCarSelectedBrand']; ?>
&car_select[model]=<?php echo ((is_array($_tmp=$this->_tpl_vars['aModel']['code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['aModel']['short_name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
</select>