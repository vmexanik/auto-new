<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:07
         compiled from manager_panel/purchase/row_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager_panel/purchase/row_details.tpl', 51, false),array('modifier', 'cat', 'manager_panel/purchase/row_details.tpl', 54, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'action'): ?>
<td nowrap>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'id_cart_package'): ?>
	<td style="white-space: nowrap;">
		#<?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?>

	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'cat_name'): ?>
	<td>
		<?php if ($this->_tpl_vars['aRow']['cat_name_changed']): ?>
			<?php echo $this->_tpl_vars['aRow']['cat_name_changed']; ?>

		<?php else: ?>
			<?php echo $this->_tpl_vars['aRow']['cat_name']; ?>

		<?php endif; ?>
	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'code'): ?>
	<td>
		<?php if ($this->_tpl_vars['aRow']['code_changed']): ?>
			<?php echo $this->_tpl_vars['aRow']['code_changed']; ?>

		<?php else: ?>
			<?php echo $this->_tpl_vars['aRow']['code']; ?>

		<?php endif; ?>
	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'provider'): ?>
	<td>
		<?php echo $this->_tpl_vars['aRow']['provider_name']; ?>

	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'term'): ?>
	<td>
		<?php echo $this->_tpl_vars['aRow']['term']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->getMessage('ds.'); ?>

	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'profit'): ?>
	<td> 
		<span id="id_profit_<?php echo $this->_tpl_vars['aRow']['id']; ?>
"><?php echo $this->_tpl_vars['aRow']['profit']; ?>
</span>
	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'order_status'): ?>
<td style="white-space:nowrap;">
	    <select style="width:140px;" class="selectpicker" name="data[order_status]" id="os_<?php echo $this->_tpl_vars['aRow']['id']; ?>
"
    	onchange="xajax_process_browse_url('/?action=manager_panel_purchase_change_status&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&sel='+this.options[this.selectedIndex].value+'&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
'); return false;">
    	<?php $_from = $this->_tpl_vars['aPurchaseDetailOrderStatus']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
    		<?php if (( $this->_tpl_vars['item'] )): ?>
    			<?php $this->assign('status', ((is_array($_tmp='status_ps_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['item']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['item']))); ?>
    		<?php else: ?>
    			<?php $this->assign('status', ''); ?>
    		<?php endif; ?>
    		<option value="<?php echo $this->_tpl_vars['item']; ?>
" <?php if ($this->_tpl_vars['item'] == $this->_tpl_vars['aRow']['order_status']): ?>selected<?php endif; ?>><?php if ($this->_tpl_vars['status']): ?><?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['status']); ?>
<?php endif; ?></option>
    	<?php endforeach; endif; unset($_from); ?>
  	</select>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'incoming'): ?>
<td>
<div class="container" style="width:234px;">
    <div class="row">
        <div class='col-sm-3' style="width:234px;">
            <div class="form-group">
                <div class='input-group date' id='datetimepicker_<?php echo $this->_tpl_vars['aRow']['id']; ?>
'>
                    <input type='text' class="form-control" value="<?php echo $this->_tpl_vars['aRow']['post_date_incoming']; ?>
"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar" onclick="InitCalendar(<?php echo $this->_tpl_vars['aRow']['id']; ?>
);return false;"></span>
                    </span>
                    <span class="glyphicon glyphicon-ok-circle" onclick="SaveCalendar(<?php echo $this->_tpl_vars['aRow']['id']; ?>
);return false;" 
                    	style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('save'); ?>
"></span>
                </div>
            </div>
        </div>
    </div>
</div>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'kurs_currency'): ?>
<td><div style="width:78px;">
		<span id="id_kurs_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" style="cursor:pointer;" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('change2'); ?>
"
			onclick="change_value(<?php echo $this->_tpl_vars['aRow']['id']; ?>
,'kurs');return false;"><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</span>
		<span id="id_kurs_edit_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" style="display:none;">
			<input id="id_kurs_value_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" value="<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
" style="width:50px;color:black;">
			<span class="glyphicon glyphicon-ok-circle" onclick="change_value_apply(<?php echo $this->_tpl_vars['aRow']['id']; ?>
,'kurs');return false;" 
				style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('save'); ?>
"></span>
		</span>
	</div>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'price_original'): ?>
<td><div style="width:78px;">
		<span id="id_price_original_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" style="cursor:pointer;" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('change2'); ?>
"
			onclick="change_value(<?php echo $this->_tpl_vars['aRow']['id']; ?>
,'price_original');return false;"><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</span>
		<span id="id_price_original_edit_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" style="display:none;">
			<input id="id_price_original_value_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" value="<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
" style="width:50px;color:black;">
			<span class="glyphicon glyphicon-ok-circle" onclick="change_value_apply(<?php echo $this->_tpl_vars['aRow']['id']; ?>
,'price_original');return false;" 
				style="float:right;font-size:20px;padding: 0 0 7px 5px;cursor:pointer;" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('save'); ?>
"></span>
		</span>
	</div>
</td>
<?php else: ?><td><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>