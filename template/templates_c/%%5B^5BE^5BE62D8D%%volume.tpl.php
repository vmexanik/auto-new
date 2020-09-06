<?php /* Smarty version 2.6.18, created on 2020-07-27 17:23:44
         compiled from car_select/volume.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'car_select/volume.tpl', 6, false),)), $this); ?>
<select class="js-select js-select-volume"
                 onchange="send_param(this.options[this.selectedIndex].value,'engine','<?php echo $this->_tpl_vars['aVolume']['volume']; ?>
'); return false;">
    <option value=""><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Volume'); ?>
</option>
    <?php $_from = $this->_tpl_vars['aCarSelectVolumeGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aCarSelectVolume']):
?>
        <?php $_from = $this->_tpl_vars['aCarSelectVolume']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aVolume']):
?>
            <option value="/?action=car_select<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'car_select/xajax_link.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&year=<?php echo $this->_tpl_vars['sCarSelectedYear']; ?>
&car_select[brand]=<?php echo $this->_tpl_vars['sCarSelectedBrand']; ?>
&car_select[model]=<?php echo ((is_array($_tmp=$this->_tpl_vars['sCarSelectedModel'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&body=<?php echo $this->_tpl_vars['sCarSelectedBody']; ?>
&volume=<?php echo $this->_tpl_vars['aVolume']['volume']; ?>
"><?php echo $this->_tpl_vars['aVolume']['new_name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
</select>