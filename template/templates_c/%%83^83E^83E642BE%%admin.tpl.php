<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:33
         compiled from addon/table/filter/admin.tpl */ ?>
<nobr>
<select id=filter_select_id name="filter_select" style="width:100px">';
        <?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sField'] => $this->_tpl_vars['aRow']):
?>
        <?php if ($this->_tpl_vars['aRow']['sOrder'] != ''): ?>
        <option value="<?php echo $this->_tpl_vars['sField']; ?>
"<?php if ($this->_tpl_vars['sFilter'] == $this->_tpl_vars['sField']): ?> selected<?php endif; ?>><?php echo $this->_tpl_vars['aRow']['sTitle']; ?>
</option>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
</select>

<input id="filter_input_id" name="filter_input" value="<?php echo $this->_tpl_vars['sFilterValue']; ?>
"> &nbsp;
<a href="?<?php echo $this->_tpl_vars['sQueryString']; ?>
"
onclick="
        xajax_process_browse_url(this.href
        +(document.getElementById('filter_input_id').value!=''?
        '&filter='+document.getElementById('filter_select_id').options[document.getElementById('filter_select_id').selectedIndex].value
        +'&filter_value='+document.getElementById('filter_input_id').value
        :'')
        );
return false;
"
><b><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Filter'); ?>
</b></a> &nbsp;
<a href="?<?php echo $this->_tpl_vars['sQueryString']; ?>
"
onclick="xajax_process_browse_url(this.href);return false;"><b><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Clear'); ?>
</b></a>

</nobr>
<br>
<nobr>

<?php echo $this->_tpl_vars['oLanguage']->GetDMessage('move to page'); ?>
:&nbsp;
<input type='text' maxlength='5' name='step_page' id='step_page' style=" width: 35px;" onkeyup="this.value = this.value.replace (/\D/gi, '').replace (/^0+/, '')">
<a href="<?php echo $this->_tpl_vars['sCustomStepUrl']; ?>
" onclick="xajax_process_browse_url(this.href+document.getElementById('step_page').value);return false;"><b><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('ok'); ?>
</b></a>
</nobr>