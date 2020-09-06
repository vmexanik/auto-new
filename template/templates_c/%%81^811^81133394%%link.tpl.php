<?php /* Smarty version 2.6.18, created on 2020-04-05 17:45:44
         compiled from car_select/link.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'car_select/link.tpl', 6, false),)), $this); ?>
<div style="display: none;"
<?php if (! $this->_tpl_vars['aCarSelectUrl']): ?>
onclick="return false;" 
class="input_select hide form_button_show jpn-button at-btn" 
<?php else: ?>
onclick="xajax_process_browse_url('/?action=catalog_save_selected_auto&<?php echo $this->_tpl_vars['sSaveLink']; ?>
'); document.location='<?php echo ((is_array($_tmp=$this->_tpl_vars['aCarSelectUrl'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
'" 
class="input_select hide form_button_show jpn-button red at-btn" 
<?php endif; ?>
id="selected_car_link">
Подобрать запчасти
</div>