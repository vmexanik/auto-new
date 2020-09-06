<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from addon/mpanel/base_row_action.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'addon/mpanel/base_row_action.tpl', 3, false),)), $this); ?>
<?php if (! $this->_tpl_vars['aAdmin']['is_base_denied']): ?>
<nobr>
<A href="?action=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_edit&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" onclick="
xajax_process_browse_url(this.href); return false;">
<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
	hspace=3 align=absmiddle><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Edit'); ?>
</A>
</nobr>
<?php endif; ?>
<?php if ($this->_tpl_vars['not_delete'] != 1 && ! $this->_tpl_vars['aAdmin']['is_base_denied'] && ( $this->_tpl_vars['sBaseAction'] != 'admin' || ( $this->_tpl_vars['sBaseAction'] == 'admin' && $this->_tpl_vars['aAdmin']['id'] != $this->_tpl_vars['aRow']['id'] ) )): ?>
<nobr>
<A href="?action=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_delete&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" onclick="if (confirm_delete_glg())
xajax_process_browse_url(this.href);  return false;">
<IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png"
		hspace=3 align=absmiddle><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Delete'); ?>
</A>
</nobr>
<?php endif; ?>