<?php /* Smarty version 2.6.18, created on 2020-04-06 12:12:49
         compiled from addon/mpanel/base_add_button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'addon/mpanel/base_add_button.tpl', 2, false),)), $this); ?>
<input type=hidden name=action value=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_apply>
<input type=hidden name=return value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">

<?php if (! $this->_tpl_vars['bHideReturn']): ?>
<input type=button value='<?php echo $this->_tpl_vars['oLanguage']->getDMessage('<< Return'); ?>
'
 onClick=" xajax_process_browse_url('?<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'); return false; " class=submit_button>
<?php endif; ?>

<INPUT type=submit class="bttn" value='<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Submit'); ?>
'>