<?php /* Smarty version 2.6.18, created on 2020-04-05 17:45:44
         compiled from car_select/brand.tpl */ ?>
<select class="js-select js-select-brand" onchange="send_param(this.options[this.selectedIndex].value, 'brands','<?php echo $this->_tpl_vars['aBrand']['title']; ?>
'); return false;">
<option value="">Выберите марку</option>
<?php $_from = $this->_tpl_vars['aCarSelectBrandGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aCarSelectBrand']):
?>
<?php $_from = $this->_tpl_vars['aCarSelectBrand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aBrand']):
?>
<option value="/?action=car_select<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'car_select/xajax_link.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&year=<?php echo $this->_tpl_vars['sCarSelectedYear']; ?>
&car_select[brand]=<?php echo $this->_tpl_vars['aBrand']['name']; ?>
"><?php echo $this->_tpl_vars['aBrand']['title']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
</select>