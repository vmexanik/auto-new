<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:04
         compiled from table/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'table/index.tpl', 12, false),array('function', 'cycle', 'table/index.tpl', 113, false),)), $this); ?>
<div class="at-user-details">
<?php if ($this->_tpl_vars['sHeader']): ?>
    <div class="header">
        <?php echo $this->_tpl_vars['sHeader']; ?>
<?php if ($this->_tpl_vars['sHint']): ?><?php echo $this->_tpl_vars['sHint']; ?>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php if ($this->_tpl_vars['sAfterTableHeader']): ?>
	<div class="after_header"><?php echo $this->_tpl_vars['sAfterTableHeader']; ?>
</div>
<?php endif; ?>
<script type="text/javascript" src="/libp/js/table.js"></script>

<?php $this->assign('iColCount', count($this->_tpl_vars['aColumn'])); ?>
<?php if ($this->_tpl_vars['bCheckVisible']): ?>
<?php $this->assign('iColCount', $this->_tpl_vars['iColCount']+1); ?>
<?php endif; ?>

<?php if ($_GET['table_error']): ?>
<div class="error_message"><?php echo $this->_tpl_vars['oLanguage']->getMessage($_GET['table_error']); ?>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['sTableMessage']): ?><div class="<?php echo $this->_tpl_vars['sTableMessageClass']; ?>
"><?php echo $this->_tpl_vars['sTableMessage']; ?>
</div><?php endif; ?>


<?php if ($this->_tpl_vars['bFormAvailable']): ?><form id="<?php echo $this->_tpl_vars['sIdForm']; ?>
" <?php echo $this->_tpl_vars['sFormHeader']; ?>
><?php endif; ?>
<?php if ($this->_tpl_vars['sPanelTemplateTop']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sPanelTemplateTop'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<table <?php if ($this->_tpl_vars['sIdTable'] != ""): ?>id="<?php echo $this->_tpl_vars['sIdTable']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['sWidth']; ?>
;border-spacing:<?php echo $this->_tpl_vars['sCellSpacing']; ?>
;padding:0px;" class="<?php echo $this->_tpl_vars['sClass']; ?>
" >

<?php if ($this->_tpl_vars['bCheckVisible'] && $this->_tpl_vars['aItem']): ?>
<tr>
	<td colspan="20"><label for="check_all" class="for_check_label"><input type="checkbox" class="check_all js-checkbox" id="check_all" name="check_all"><span class="text"><?php echo $this->_tpl_vars['oLanguage']->getMessage('select_all'); ?>
</span></label></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['bHeaderVisible']): ?>
<tr>
	<?php if ($this->_tpl_vars['bCheckVisible']): ?><th <?php if ($this->_tpl_vars['bCheckAllVisible']): ?>style="white-space:nowrap;"<?php endif; ?>><?php if ($this->_tpl_vars['bCheckAllVisible']): ?><label><input type=checkbox class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
" name=check_all
		onclick="mt.SetCheckboxes(this.form,this.checked);<?php if ($this->_tpl_vars['sCheckAllAction'] != ''): ?><?php echo $this->_tpl_vars['sCheckAllAction']; ?>
;<?php endif; ?>"
		 <?php if ($this->_tpl_vars['bDefaultChecked']): ?>checked<?php endif; ?> ><?php endif; ?><?php if ($this->_tpl_vars['sMarkAllText']): ?>&nbsp;<?php echo $this->_tpl_vars['sMarkAllText']; ?>
<?php endif; ?>
		 </label></th>
	<?php endif; ?>


<?php if ($this->_tpl_vars['sTitleOrderLink']): ?>
	<?php $this->assign('title_order_link', " title='".($this->_tpl_vars['sTitleOrderLink'])."' "); ?>
<?php endif; ?>

<?php $_from = $this->_tpl_vars['aColumn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aValue']):
?>
	<?php echo '<th style="'; ?><?php if ($this->_tpl_vars['bHeaderNobr']): ?><?php echo 'white-space:nowrap;'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['aValue']['sWidth']): ?><?php echo 'width:'; ?><?php echo $this->_tpl_vars['aValue']['sWidth']; ?><?php echo ''; ?><?php endif; ?><?php echo '" '; ?><?php if ($this->_tpl_vars['aValue']['sHeaderClassSelect']): ?><?php echo 'class="'; ?><?php echo $this->_tpl_vars['aValue']['sHeaderClassSelect']; ?><?php echo '"'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['aValue']['sClass']): ?><?php echo ' class="'; ?><?php echo $this->_tpl_vars['aValue']['sClass']; ?><?php echo '"'; ?><?php endif; ?><?php echo ''; ?><?php echo $this->_tpl_vars['aValue']['sAdditionalHtml']; ?><?php echo '>'; ?><?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?><?php echo '<a href=\''; ?><?php if (! $this->_tpl_vars['bNoneDotUrl']): ?><?php echo '.'; ?><?php endif; ?><?php echo '/?'; ?><?php echo $this->_tpl_vars['aValue']['sOrderLink']; ?><?php echo '\' '; ?><?php echo $this->_tpl_vars['title_order_link']; ?><?php echo '>'; ?><?php endif; ?><?php echo ''; ?><?php echo $this->_tpl_vars['aValue']['sTitle']; ?><?php echo ''; ?><?php if (! $this->_tpl_vars['aValue']['sTitle']): ?><?php echo '&nbsp;'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?><?php echo ''; ?><?php if ($this->_tpl_vars['aValue']['sOrderImage']): ?><?php echo '<img src=\''; ?><?php echo $this->_tpl_vars['aValue']['sOrderImage']; ?><?php echo '\' style="margin-right:1px;margin-left:1px;">'; ?><?php endif; ?><?php echo '</a>'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['aValue']['sHint']): ?><?php echo ''; ?><?php echo $this->_tpl_vars['oLanguage']->GetContextHint($this->_tpl_vars['aValue']['sHint']); ?><?php echo ''; ?><?php endif; ?><?php echo '</th>'; ?>

<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['bCheckRightVisible']): ?><th style="<?php if ($this->_tpl_vars['bCheckAllVisible']): ?>white-space:nowrap;<?php endif; ?>"><?php if ($this->_tpl_vars['bCheckAllVisible']): ?><label><input type=checkbox name=check_all class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
" 
		onclick="mt.SetCheckboxes(this.form,this.checked);<?php if ($this->_tpl_vars['sCheckAllAction'] != ''): ?><?php echo $this->_tpl_vars['sCheckAllAction']; ?>
;<?php endif; ?>"
		 <?php if ($this->_tpl_vars['bDefaultChecked']): ?>checked<?php endif; ?> ><?php endif; ?><?php if ($this->_tpl_vars['sMarkAllText']): ?>&nbsp;<?php echo $this->_tpl_vars['sMarkAllText']; ?>
<?php endif; ?>
		 </label></th>
	<?php endif; ?>
</tr>
<?php elseif ($this->_tpl_vars['bHeaderGroupVisible']): ?>
<tr>
	<?php if ($this->_tpl_vars['bCheckVisible']): ?><th style="<?php if ($this->_tpl_vars['bCheckAllVisible']): ?>white-space:nowrap;<?php endif; ?>"><?php if ($this->_tpl_vars['bCheckAllVisible']): ?><label><input type=checkbox name=check_all class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
"
		onclick="mt.SetCheckboxes(this.form,this.checked);<?php if ($this->_tpl_vars['sCheckAllAction'] != ''): ?><?php echo $this->_tpl_vars['sCheckAllAction']; ?>
;<?php endif; ?>"
		 <?php if ($this->_tpl_vars['bDefaultChecked']): ?>checked<?php endif; ?> ><?php endif; ?><?php if ($this->_tpl_vars['sMarkAllText']): ?>&nbsp;<?php echo $this->_tpl_vars['sMarkAllText']; ?>
<?php endif; ?>
		 </label></th>
	<?php endif; ?>
<?php $_from = $this->_tpl_vars['aColumn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aValue']):
?>
	<?php if (! $this->_tpl_vars['aValue']['bGroup']): ?>
	<th style="white-space:nowrap;<?php if ($this->_tpl_vars['aValue']['sWidth']): ?>width:<?php echo $this->_tpl_vars['aValue']['sWidth']; ?>
;<?php endif; ?>" <?php if ($this->_tpl_vars['aValue']['sHeaderClassSelect']): ?>class="<?php echo $this->_tpl_vars['aValue']['sHeaderClassSelect']; ?>
"<?php endif; ?>
	 rowspan="2"
	 <?php echo $this->_tpl_vars['aValue']['sAdditionalHtml']; ?>
>
	<?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?><a href='<?php if (! $this->_tpl_vars['bNoneDotUrl']): ?>.<?php endif; ?>/?<?php echo $this->_tpl_vars['aValue']['sOrderLink']; ?>
'><?php endif; ?>
	<?php echo $this->_tpl_vars['aValue']['sTitle']; ?>
<?php if (! $this->_tpl_vars['aValue']['sTitle']): ?>&nbsp;<?php endif; ?>
	<?php if ($this->_tpl_vars['aValue']['sOrderLink']): ?><?php if ($this->_tpl_vars['aValue']['sOrderImage']): ?><img src='<?php echo $this->_tpl_vars['aValue']['sOrderImage']; ?>
' style="margin-right:1px;margin-left:1px;"><?php endif; ?>
	</a><?php endif; ?>
	</th>
	<?php else: ?>
	<?php echo $this->_tpl_vars['aValue']['sGroupTitle']; ?>

	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['bCheckRightVisible']): ?><th style="<?php if ($this->_tpl_vars['bCheckAllVisible']): ?>white-space:nowrap;<?php endif; ?>"><?php if ($this->_tpl_vars['bCheckAllVisible']): ?><label><input type=checkbox name=check_all class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
"
		onclick="mt.SetCheckboxes(this.form,this.checked);<?php if ($this->_tpl_vars['sCheckAllAction'] != ''): ?><?php echo $this->_tpl_vars['sCheckAllAction']; ?>
;<?php endif; ?>"
		 <?php if ($this->_tpl_vars['bDefaultChecked']): ?>checked<?php endif; ?> ><?php endif; ?><?php if ($this->_tpl_vars['sMarkAllText']): ?>&nbsp;<?php echo $this->_tpl_vars['sMarkAllText']; ?>
<?php endif; ?>
		 </label></th>
	<?php endif; ?>
</tr>
<tr>
<?php $_from = $this->_tpl_vars['aColumn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['aValue']):
?>
	<?php if ($this->_tpl_vars['aValue']['bGroup']): ?>
	<th style="white-space:nowrap;<?php if ($this->_tpl_vars['aValue']['sWidth']): ?>width:<?php echo $this->_tpl_vars['aValue']['sWidth']; ?>
;<?php endif; ?>" <?php if ($this->_tpl_vars['aValue']['sHeaderClassSelect']): ?>class="<?php echo $this->_tpl_vars['aValue']['sHeaderClassSelect']; ?>
"<?php endif; ?>
	 <?php echo $this->_tpl_vars['aValue']['sAdditionalHtml']; ?>
>
	<?php echo $this->_tpl_vars['aValue']['sTitle']; ?>
<?php if (! $this->_tpl_vars['aValue']['sTitle']): ?>&nbsp;<?php endif; ?>
	</th>
	<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['sSubtotalTemplateTop']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sSubtotalTemplateTop'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php $this->assign('iTr', '0'); ?>
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
<?php $this->assign('iTr', $this->_tpl_vars['iTr']+1); ?>
<tr <?php if ($this->_tpl_vars['aItem'][$this->_sections['d']['index']]['tr_add_text']): ?><?php echo $this->_tpl_vars['aItem'][$this->_sections['d']['index']]['tr_add_text']; ?>
<?php endif; ?> id="<?php echo $this->_tpl_vars['sIdiTr']; ?>
<?php echo $this->_tpl_vars['iTr']; ?>
" <?php if ($this->_tpl_vars['bHideTr']): ?> pn="<?php echo $this->_tpl_vars['aItem'][$this->_sections['d']['index']]['iHideTr']; ?>
"<?php endif; ?>
	<?php if ($this->_tpl_vars['aItem'][$this->_sections['d']['index']]['class_tr']): ?>class="<?php echo $this->_tpl_vars['aItem'][$this->_sections['d']['index']]['class_tr']; ?>
<?php else: ?>class="<?php echo smarty_function_cycle(array('values' => "none,even"), $this);?>
<?php endif; ?>
	<?php if ($this->_tpl_vars['bDefaultChecked']): ?> <?php echo $this->_tpl_vars['aRow']['sClassCheckTr']; ?>
<?php elseif ($this->_tpl_vars['aRow']['bCheckTr']): ?> <?php echo $this->_tpl_vars['aRow']['sClassCheckTr']; ?>
<?php endif; ?>
	"
	<?php if ($this->_tpl_vars['aItem'][$this->_sections['d']['index']]['hide_tr'] == '1'): ?>style="display: none;"<?php elseif ($this->_tpl_vars['aItem'][$this->_sections['d']['index']]['style_tr']): ?>style="<?php echo $this->_tpl_vars['aItem'][$this->_sections['d']['index']]['style_tr']; ?>
"<?php endif; ?>
	<?php if ($this->_tpl_vars['bCheckVisible'] && $this->_tpl_vars['bCheckOnClick']): ?>onclick="var ch=getCookie('checkbox'); setCookie('checkbox','0',1);if(ch=='1') return true; var c=$('#row_check_<?php echo $this->_sections['d']['index']; ?>
');c.prop('checked', !c.prop('checked')); return false;"<?php endif; ?>
	<?php if ($this->_tpl_vars['aItem'][$this->_sections['d']['index']]['js_tr']): ?><?php echo $this->_tpl_vars['aItem'][$this->_sections['d']['index']]['js_tr']; ?>
<?php endif; ?>>

	<?php if ($this->_tpl_vars['bCheckVisible']): ?><td><?php if (! $this->_tpl_vars['aRow']['bCheckHide']): ?><label><input type=checkbox name=row_check[] class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
"
	id='row_check_<?php echo $this->_sections['d']['index']; ?>
' value='<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sCheckField']]; ?>
'
	<?php if ($this->_tpl_vars['bDefaultChecked']): ?> checked<?php elseif ($this->_tpl_vars['aRow']['bCheckTr']): ?> checked<?php endif; ?>
	<?php if ($this->_tpl_vars['bCheckVisible'] && $this->_tpl_vars['bCheckOnClick']): ?>onclick="setCookie('checkbox','1',1);"<?php endif; ?>
	<?php if ($this->_tpl_vars['sCheckAction'] != ''): ?>onchange="<?php echo $this->_tpl_vars['sCheckAction']; ?>
"<?php endif; ?>><?php endif; ?></label></td>
	<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sDataTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php if ($this->_tpl_vars['bCheckRightVisible']): ?><td><?php if (! $this->_tpl_vars['aRow']['bCheckHide']): ?><input type=checkbox name=row_check[] class="<?php echo $this->_tpl_vars['sCheckAllClass']; ?>
"
	id='row_check_<?php echo $this->_sections['d']['index']; ?>
' value='<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sCheckField']]; ?>
'
	<?php if ($this->_tpl_vars['bDefaultChecked']): ?> checked<?php elseif ($this->_tpl_vars['aRow']['bCheckTr']): ?> checked<?php endif; ?>
	<?php if ($this->_tpl_vars['bCheckVisible'] && $this->_tpl_vars['bCheckOnClick']): ?>onclick="setCookie('checkbox','1',1);"<?php endif; ?>
	<?php if ($this->_tpl_vars['sCheckAction'] != ''): ?>onchange="<?php echo $this->_tpl_vars['sCheckAction']; ?>
"<?php endif; ?>><?php endif; ?></td>
	<?php endif; ?>
</tr>
<?php endfor; endif; ?>


<?php if (! $this->_tpl_vars['aItem']): ?>
<tr>
	<td class="even" colspan="<?php echo $this->_tpl_vars['iColCount']; ?>
">
	<?php if ($this->_tpl_vars['sNoItem']): ?>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['sNoItem']); ?>

	<?php else: ?>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage('No items found'); ?>

	<?php endif; ?>
	</td>
	<?php if ($this->_tpl_vars['bCheckVisible']): ?>
		<td>
		</td>
	<?php endif; ?>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['sSubtotalTemplate']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sSubtotalTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php if ($this->_tpl_vars['bShowRowPerPage']): ?>
<tr>
	<td colspan="<?php echo $this->_tpl_vars['iColCount']; ?>
" style="text-align:right;">
	<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Display #'); ?>

<select id=display_select_id name=display_select style="width: 50px;"
	onchange="<?php echo 'javascript:location.href=\'/?'; ?><?php echo $this->_tpl_vars['sActionRowPerPage']; ?><?php echo '&content=\'+document.getElementById(\'display_select_id\').options[document.getElementById(\'display_select_id\').selectedIndex].value;'; ?>
">
	<option value=10 <?php if ($this->_tpl_vars['iRowPerPage'] == 10): ?> selected<?php endif; ?>>10</option>
    <option value=20 <?php if ($this->_tpl_vars['iRowPerPage'] == 20 || ! $this->_tpl_vars['iRowPerPage']): ?> selected<?php endif; ?>>20</option>
    <option value=50 <?php if ($this->_tpl_vars['iRowPerPage'] == 50): ?> selected<?php endif; ?>>50</option>
    <option value=100 <?php if ($this->_tpl_vars['iRowPerPage'] == 100): ?> selected<?php endif; ?>>100</option>
    <?php if ($this->_tpl_vars['bShowPerPageAll']): ?><option value=10000 <?php if ($this->_tpl_vars['iRowPerPage'] == 10000): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['oLanguage']->getMessage('all'); ?>
</option><?php endif; ?>
</select>

<span class="stepper_results"><?php echo $this->_tpl_vars['oLanguage']->getDMessage('Results'); ?>
 <?php echo $this->_tpl_vars['iStartRow']; ?>
 - <?php if ($this->_tpl_vars['iEndRow'] == 10000 && $this->_tpl_vars['iAllRow'] < 10000): ?><?php echo $this->_tpl_vars['iAllRow']; ?>
<?php else: ?><?php echo $this->_tpl_vars['iEndRow']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['oLanguage']->getDMessage('of'); ?>
 <?php echo $this->_tpl_vars['iAllRow']; ?>
</span>
	</td>
</tr>
<?php endif; ?>

</table>
</div>

<?php if ($this->_tpl_vars['sStepper']): ?>
	<?php echo $this->_tpl_vars['sStepper']; ?>

<?php endif; ?>

<div style="padding: 5px;">
<?php if ($this->_tpl_vars['sButtonTemplate']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sButtonTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php if ($this->_tpl_vars['sAddButton']): ?>
<span <?php if ($this->_tpl_vars['sButtonSpanClass']): ?>class="button"<?php endif; ?>>
<input type=button class='at-btn' value="<?php echo $this->_tpl_vars['sAddButton']; ?>
" onclick="location.href='<?php if (! $this->_tpl_vars['bNoneDotUrl']): ?>.<?php endif; ?>/?action=<?php echo $this->_tpl_vars['sAddAction']; ?>
'" >
</span>
<?php endif; ?>
</div>


<?php if ($this->_tpl_vars['bFormAvailable']): ?>
<input type="hidden" name="action" id='action' value='<?php if ($this->_tpl_vars['sFormAction']): ?><?php echo $this->_tpl_vars['sFormAction']; ?>
<?php else: ?>empty<?php endif; ?>'>
<input type="hidden" name="return" id='return' value='<?php echo $this->_tpl_vars['sReturn']; ?>
'>
</form>
<?php endif; ?>