<?php /* Smarty version 2.6.18, created on 2020-06-08 19:22:49
         compiled from car_select/modif.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'car_select/modif.tpl', 4, false),)), $this); ?>
<select class="js-select js-select-modification" onchange="send_param(this.options[this.selectedIndex].value,'modif','<?php echo $this->_tpl_vars['aModif']['name']; ?>
'); return false;">
<option value="">Двигатель</option>
<?php $_from = $this->_tpl_vars['aCarSelectModifGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['aCarSelectModif']):
?>
	<?php if (count($this->_tpl_vars['aCarSelectModifGroup']) > 1): ?>
    	<optgroup label="<?php echo $this->_tpl_vars['sKey']; ?>
 выпуск <?php echo $this->_tpl_vars['aCarSelectModif']['0']['start_end']; ?>
 гг.">
    <?php endif; ?>
		<?php $_from = $this->_tpl_vars['aCarSelectModif']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aModif']):
?>
			<option value="/?action=car_select<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'car_select/xajax_link.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&year=<?php echo $this->_tpl_vars['sCarSelectedYear']; ?>
&car_select[brand]=<?php echo $this->_tpl_vars['sCarSelectedBrand']; ?>
&car_select[model]=<?php echo $this->_tpl_vars['sCarSelectedModel']; ?>
&body=<?php echo $this->_tpl_vars['sCarSelectedBody']; ?>
&volume=<?php echo $this->_tpl_vars['sCarSelectedVolume']; ?>
&modification=<?php echo $this->_tpl_vars['aModif']['id']; ?>
"><?php echo $this->_tpl_vars['aModif']['name']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	<?php if (count($this->_tpl_vars['aCarSelectModifGroup']) > 1): ?>
	   	</optgroup>
	<?php endif; ?>

<?php endforeach; endif; unset($_from); ?>
</select>