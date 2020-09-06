<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from form/index_search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'form/index_search.tpl', 4, false),)), $this); ?>
<table>
<tbody>
<?php $_from = $this->_tpl_vars['aField']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['main'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['main']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['aItem']):
        $this->_foreach['main']['iteration']++;
?>
<?php $this->assign('field_template', ((is_array($_tmp=((is_array($_tmp='form/')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aItem']['type']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aItem']['type'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '.tpl') : smarty_modifier_cat($_tmp, '.tpl'))); ?>
	<?php if ($this->_tpl_vars['aItem']['type'] == 'hidden'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['field_template'], 'smarty_include_vars' => array('aInput' => $this->_tpl_vars['aItem'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
    <tr>
        <?php if ($this->_tpl_vars['aItem']['title']): ?>
        <td>
            <b><?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['aItem']['title']); ?>
:</b><?php if ($this->_tpl_vars['aItem']['szir']): ?><?php echo $this->_tpl_vars['sZir']; ?>
<?php endif; ?>
			<?php if ($this->_tpl_vars['aItem']['contexthint']): ?> <?php echo $this->_tpl_vars['oLanguage']->getContextHint($this->_tpl_vars['aItem']['contexthint']); ?>
<?php endif; ?>
		</td>
		<?php endif; ?>
		<td>
		   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['field_template'], 'smarty_include_vars' => array('aInput' => $this->_tpl_vars['aItem'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php if ($this->_tpl_vars['aItem']['br']): ?><br /><?php endif; ?>
		   <?php if ($this->_tpl_vars['aItem']['add_to_td']): ?>
					<?php $_from = $this->_tpl_vars['aItem']['add_to_td']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['add_to_td'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['add_to_td']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['aItem2']):
        $this->_foreach['add_to_td']['iteration']++;
?>
						<?php $this->assign('field_template2', ((is_array($_tmp=((is_array($_tmp='form/')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aItem2']['type']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aItem2']['type'])))) ? $this->_run_mod_handler('cat', true, $_tmp, '.tpl') : smarty_modifier_cat($_tmp, '.tpl'))); ?>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['field_template2'], 'smarty_include_vars' => array('aInput' => $this->_tpl_vars['aItem2'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php if ($this->_tpl_vars['aItem2']['br']): ?><br /><?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>  
				<?php endif; ?>
		</td>
    </tr>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</tbody>
</table>