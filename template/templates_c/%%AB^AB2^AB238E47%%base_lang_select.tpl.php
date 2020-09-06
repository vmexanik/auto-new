<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:34
         compiled from addon/mpanel/base_lang_select.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'addon/mpanel/base_lang_select.tpl', 2, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['aLocaleGlobal']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
<a href="?action=locale_global_edit&locale=<?php echo $this->_tpl_vars['aItem']['code']; ?>
&table_name=<?php echo $this->_tpl_vars['sTableName']; ?>
&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
	onclick="xajax_process_browse_url(this.href);  return false;"
><img src="<?php echo $this->_tpl_vars['aItem']['image']; ?>
" width=16 hspace=2 border=0></a>
<?php endforeach; endif; unset($_from); ?>