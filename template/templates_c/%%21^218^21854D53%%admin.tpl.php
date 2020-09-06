<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from addon/table/admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'addon/table/admin.tpl', 30, false),)), $this); ?>
<?php if ($this->_tpl_vars['bFormAvailable']): ?><form id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)"><?php endif; ?>
<?php if ($this->_tpl_vars['sTableMessage']): ?><div class="<?php echo $this->_tpl_vars['sTableMessageClass']; ?>
"><?php echo $this->_tpl_vars['sTableMessage']; ?>
</div><?php endif; ?>
<table class="itemslist" id='admin_itemslist_table'>
<tbody>
<?php if ($this->_tpl_vars['bHeaderVisible']): ?>
<tr>
	<?php if ($this->_tpl_vars['bCheckVisible']): ?><th width="20">
		<input name="check_all" id="all" value="all" type="checkbox" <?php if ($this->_tpl_vars['bDefaultChecked']): ?>checked<?php endif; ?>>
	</th><?php endif; ?>

<?php $_from = $this->_tpl_vars['aColumn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aValue']):
?>
	<th <?php if ($this->_tpl_vars['aValue']['sWidth']): ?> width="<?php echo $this->_tpl_vars['aValue']['sWidth']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['aValue']['sOrderImage']): ?>class="sel"<?php endif; ?> nowrap>
	<?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?>
	<a href='./?<?php echo $this->_tpl_vars['aValue']['sOrderLink']; ?>
' <?php if ($this->_tpl_vars['bAjaxStepper']): ?>onclick=" xajax_process_browse_url(this.href); return false;"<?php endif; ?>
		><?php endif; ?>
	<?php echo $this->_tpl_vars['aValue']['sTitle']; ?>


	<?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?>
		<?php if ($this->_tpl_vars['aValue']['sOrderImage']): ?><img src='<?php echo $this->_tpl_vars['aValue']['sOrderImage']; ?>
' border=0 hspace=1><?php endif; ?>
	</a><?php endif; ?>

	<?php if (! $this->_tpl_vars['aValue']['sTitle']): ?>&nbsp;<?php endif; ?></th>
<?php endforeach; endif; unset($_from); ?>
</tr>
<?php endif; ?>

<?php $this->assign('num', 1); ?>
<?php unset($this->_sections['d']);
$this->_sections['d']['name'] = 'd';
$this->_sections['d']['loop'] = is_array($_loop=$this->_tpl_vars['aItem']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['d']['show'] = true;
$this->_sections['d']['max'] = $this->_sections['d']['loop'];
$this->_sections['d']['step'] = 1;
$this->_sections['d']['start'] = $this->_sections['d']['step'] > 0 ? 0 : $this->_sections['d']['loop']-1;
if ($this->_sections['d']['show']) {
    $this->_sections['d']['total'] = $this->_sections['d']['loop'];
    if ($this->_sections['d']['total'] == 0)
        $this->_sections['d']['show'] = false;
} else
    $this->_sections['d']['total'] = 0;
if ($this->_sections['d']['show']):

            for ($this->_sections['d']['index'] = $this->_sections['d']['start'], $this->_sections['d']['iteration'] = 1;
                 $this->_sections['d']['iteration'] <= $this->_sections['d']['total'];
                 $this->_sections['d']['index'] += $this->_sections['d']['step'], $this->_sections['d']['iteration']++):
$this->_sections['d']['rownum'] = $this->_sections['d']['iteration'];
$this->_sections['d']['index_prev'] = $this->_sections['d']['index'] - $this->_sections['d']['step'];
$this->_sections['d']['index_next'] = $this->_sections['d']['index'] + $this->_sections['d']['step'];
$this->_sections['d']['first']      = ($this->_sections['d']['iteration'] == 1);
$this->_sections['d']['last']       = ($this->_sections['d']['iteration'] == $this->_sections['d']['total']);
?>
<?php $this->assign('aRow', $this->_tpl_vars['aItem'][$this->_sections['d']['index']]); ?>
<tr class="<?php echo smarty_function_cycle(array('values' => "even,none"), $this);?>
">
	<?php if ($this->_tpl_vars['bCheckVisible']): ?>
		<td><?php if ($this->_tpl_vars['sBaseAction'] != 'admin' || ( $this->_tpl_vars['sBaseAction'] == 'admin' && $this->_tpl_vars['aRow']['login'] && $this->_tpl_vars['aRow']['login'] != $this->_tpl_vars['CheckLogin'] && $this->_tpl_vars['aAdmin']['id'] != $this->_tpl_vars['aRow']['id'] )): ?><input name="row_check[<?php echo $this->_tpl_vars['num']; ?>
]" value="<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sCheckField']]; ?>
" type="checkbox"><?php endif; ?></td>
	<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sDataTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</tr>
<?php $this->assign('num', $this->_tpl_vars['num']+1); ?>
<?php endfor; endif; ?>


<?php if (! $this->_tpl_vars['aItem']): ?>
<tr>
	<td class=even colspan=20>
	<?php if ($this->_tpl_vars['sNoItem']): ?>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['sNoItem']); ?>

	<?php else: ?>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage('No items found'); ?>

	<?php endif; ?>
	</td>
</tr>
<?php endif; ?>
</tbody>
</table>
<table style="width: 100%;">
<tbody>
<?php if ($this->_tpl_vars['sSubtotalTemplate']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sSubtotalTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php if ($this->_tpl_vars['sStepper']): ?>
<?php echo '

'; ?>

<tr class="stepper">
	<?php if ($this->_tpl_vars['sLeftFilter']): ?>
	<td colspan=<?php echo $this->_tpl_vars['iColspanFilter']; ?>
><?php echo $this->_tpl_vars['sLeftFilter']; ?>
</td>
	<?php endif; ?>
	<td colspan="20" align="right" class="pages">
	<?php echo $this->_tpl_vars['sStepper']; ?>

	</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['bShowRowPerPage']): ?>
<tr>
	<td><nobr>
    <input type=checkbox id=search_strong_id name=data[search_strong] value='1' style="width:22px;" <?php if ($this->_tpl_vars['oLanguage']->getConstant('mpanel_search_strong',0)): ?>checked<?php endif; ?>
    onchange="javascript:
	xajax_process_browse_url('?action=admin_search_strong_change&status='+document.getElementById('search_strong_id').checked); return false;">
    <?php echo $this->_tpl_vars['oLanguage']->getDMessage('Searh strong'); ?>

    </td>
	<td colspan="20" align="right">
	<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Display #'); ?>

<select id=display_select_id name=display_select style="width: 50px;"
	onchange="javascript:
xajax_process_browse_url('?action=<?php echo $this->_tpl_vars['sAction']; ?>
_display_change&content='+document.getElementById('display_select_id').options[document.getElementById('display_select_id').selectedIndex].value); return false;">
	<option value=5 <?php if ($this->_tpl_vars['iRowPerPage'] == 5): ?> selected<?php endif; ?>>5</option>
    <option value=10 <?php if ($this->_tpl_vars['iRowPerPage'] == 10 || ! $this->_tpl_vars['iRowPerPage']): ?> selected<?php endif; ?>>10</option>
    <option value=20 <?php if ($this->_tpl_vars['iRowPerPage'] == 20): ?> selected<?php endif; ?>>20</option>
    <option value=30 <?php if ($this->_tpl_vars['iRowPerPage'] == 30): ?> selected<?php endif; ?>>30</option>
    <option value=50 <?php if ($this->_tpl_vars['iRowPerPage'] == 50): ?> selected<?php endif; ?>>50</option>
    <option value=100 <?php if ($this->_tpl_vars['iRowPerPage'] == 100): ?> selected<?php endif; ?>>100</option>
    <option value=200 <?php if ($this->_tpl_vars['iRowPerPage'] == 200): ?> selected<?php endif; ?>>200</option>
    <option value=500 <?php if ($this->_tpl_vars['iRowPerPage'] == 500): ?> selected<?php endif; ?>>500</option>
    <option value=1000 <?php if ($this->_tpl_vars['iRowPerPage'] == 1000): ?> selected<?php endif; ?>>1000</option>
</select>

<span class=stepper_results><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Results'); ?>
 <?php echo $this->_tpl_vars['iStartRow']; ?>
 - <?php echo $this->_tpl_vars['iEndRow']; ?>
 of <?php echo $this->_tpl_vars['iAllRow']; ?>
</span>
	</td>
</tr>
<?php endif; ?>
</tbody>
</table>
<div style="padding: 5px;">
<?php if ($this->_tpl_vars['sButtonTemplate']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sButtonTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php if ($this->_tpl_vars['sAddButton']): ?>
<input type=button value="<?php echo $this->_tpl_vars['sAddButton']; ?>
" onclick="location.href='./?action=<?php echo $this->_tpl_vars['sAddAction']; ?>
'" >
<?php endif; ?>
</div>

<?php if ($this->_tpl_vars['bFormAvailable']): ?>
<input type=hidden name=action id='action' value='empty'>
<input type=hidden name=return id='return' value=''>
</form>
<?php endif; ?>