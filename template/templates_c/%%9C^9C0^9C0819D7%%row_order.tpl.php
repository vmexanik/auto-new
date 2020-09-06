<?php /* Smarty version 2.6.18, created on 2019-09-27 17:37:53
         compiled from manager_panel/manager_package_list/row_order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager_panel/manager_package_list/row_order.tpl', 18, false),array('modifier', 'in_array', 'manager_panel/manager_package_list/row_order.tpl', 44, false),array('modifier', 'date_format', 'manager_panel/manager_package_list/row_order.tpl', 83, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'action'): ?>
<td nowrap>
	<span class="glyphicon glyphicon-file" aria-hidden="true" style="font-size:14px;cursor:pointer"
		onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
'); return false;">
	</span>
	<a href="/?action=manager_panel_print_order&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
" onclick="xajax_process_browse_url(this.href); return false;">
	<img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" style="padding-bottom:3px;">
	</a>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'id'): ?>
<td style="white-space: nowrap;">
<?php echo $this->_tpl_vars['oLanguage']->getMessage('cartpackage #'); ?>
 #<?php echo $this->_tpl_vars['aRow']['id']; ?>

<?php if ($this->_tpl_vars['aRow']['is_web_order']): ?><span><img src="/image/design/globe_icon.png" border=0 width=16 align=absmiddle></span><?php endif; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'name'): ?>
<td>
<div style="overflow:overlay;font-size:11px;max-width:130px;word-wrap: break-word;">
<span>
    <?php if ($this->_tpl_vars['aRow']['order_status'] == 'new' || $this->_tpl_vars['oContent']->CheckAccessManager(false,'manager_package_edit_detail')): ?>
	<a href="/?action=manager_edit_weight&id_cart=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&item_code=<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
&name=<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
    <?php endif; ?>
	<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>

	<br><font color="#9B9B9B"><?php echo $this->_tpl_vars['aRow']['customer_comment']; ?>
</font>
</span>
</div>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'provider'): ?>
<td>
	<?php if ($this->_tpl_vars['aAllowChangeProviderDetailStatus'] && ((is_array($_tmp=$this->_tpl_vars['aRow']['order_status'])) ? $this->_run_mod_handler('in_array', true, $_tmp, $this->_tpl_vars['aAllowChangeProviderDetailStatus']) : in_array($_tmp, $this->_tpl_vars['aAllowChangeProviderDetailStatus']))): ?>
	<a href="/?action=manager_change_provider&id_cart=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
	<?php endif; ?>
	<span onmouseover="$('#provider_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
_<?php echo $this->_tpl_vars['aRow']['id']; ?>
').toggle();" onmouseout="$('#provider_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
_<?php echo $this->_tpl_vars['aRow']['id']; ?>
').toggle();">
		<a href="/?action=manager_order&search[id_provider]=<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
"><?php echo $this->_tpl_vars['aRow']['provider_name']; ?>
</a>
		<div align="left" style="width:auto;padding: 5px; display: none;" class="tip_div" id="provider_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
_<?php echo $this->_tpl_vars['aRow']['id']; ?>
">
			<p>Баланс:<br>
				<?php if ($this->_tpl_vars['aRow']['id_group_provider']): ?>
					<?php if ($this->_tpl_vars['aRow']['provider_group_account_amount'] > 0): ?>
						<b><font color="green"><?php echo $this->_tpl_vars['aRow']['provider_group_account_amount']; ?>
</font></b> (Предоплата)
					<?php elseif ($this->_tpl_vars['aRow']['provider_group_account_amount'] == 0): ?>
						<b><font color="black">0,00</font></b>
					<?php else: ?>
						<b><font color="red"><?php echo $this->_tpl_vars['aRow']['provider_group_account_amount']; ?>
</font></b> (Долг)
					<?php endif; ?>			
				<?php else: ?>
					<?php if ($this->_tpl_vars['aRow']['provider_account_amount'] > 0): ?>
						<b><font color="green"><?php echo $this->_tpl_vars['aRow']['provider_account_amount']; ?>
</font></b> (Предоплата)
					<?php elseif ($this->_tpl_vars['aRow']['provider_account_amount'] == 0): ?>
						<b><font color="black">0,00</font></b>
					<?php else: ?>
						<b><font color="red"><?php echo $this->_tpl_vars['aRow']['provider_account_amount']; ?>
</font></b> (Долг)
					<?php endif; ?>
				<?php endif; ?>
			</p>
		</div>
	</span>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'customer_name'): ?>
<td style="white-space:nowrap;">
	<?php if ($this->_tpl_vars['aRow']['customer_name']): ?><?php echo $this->_tpl_vars['aRow']['customer_name']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['login']; ?>
<?php endif; ?>
	<?php if ($this->_tpl_vars['aRow']['phone']): ?><br>(<?php echo $this->_tpl_vars['aRow']['phone']; ?>
)<?php endif; ?>
	<?php if ($this->_tpl_vars['aRow']['user_is_new']): ?><span class="label label-danger">new</span><?php endif; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'cp_post_date_f'): ?>
<td><?php if ($this->_tpl_vars['aRow']['order_status'] == 'return_store' || $this->_tpl_vars['aRow']['order_status'] == 'return_provider'): ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['aRow']['post'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m %H:%S") : smarty_modifier_date_format($_tmp, "%d.%m %H:%S")); ?>

	<?php else: ?>
		<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>

	<?php endif; ?>
	</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'term'): ?>
<td> <?php echo $this->_tpl_vars['aRow']['term']; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'buh_balance'): ?>
<td> <a href='/?action=buh_changeling&search[id_buh]=361&search[id_subconto1]=<?php echo $this->_tpl_vars['aRow']['id_user']; ?>
' target=_blank>
	<font color="red"><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['buh_balance']); ?>
</font></a>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'debt'): ?>
<td> <?php if ($this->_tpl_vars['aRow']['buh_balance'] < $this->_tpl_vars['aRow']['total']): ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total']-$this->_tpl_vars['aRow']['buh_balance']); ?>
<?php else: ?>0<?php endif; ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'total_original'): ?>
<td> <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_original']); ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'profit'): ?>
<td> <?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aRow']['profit']); ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'price_total'): ?>
<td><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total']); ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'created'): ?>
<td style="white-space:nowrap;">
	<?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'changed'): ?>
<td style="white-space:nowrap;">
	<?php if ($this->_tpl_vars['aRow']['post_date_changed'] != '0000-00-00 00:00:00'): ?><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['created']; ?>
<?php endif; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'order_status'): ?>
<td style="white-space:nowrap; " id="order_status_select_block_<?php echo $this->_tpl_vars['aRow']['id']; ?>
">
	<?php if ($this->_tpl_vars['aRow']['order_status_select']): ?>
		<?php echo $this->_tpl_vars['aRow']['order_status_select']; ?>

	<?php else: ?>
		<?php echo $this->_tpl_vars['oContent']->getOrderStatus($this->_tpl_vars['aRow']['order_status']); ?>

	<?php endif; ?>
	<?php if ($this->_tpl_vars['aRow']['history']): ?>
	<br><nobr>
	<strong><a href="#" onmouseover="show_hide('history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
','inline')" onmouseout="show_hide('history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
','none')"
		onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage('History'); ?>
</a>&nbsp;</strong></nobr>
	<div style="display: none;width:auto; " align=left class="status_div" id="history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
">
		<?php $_from = $this->_tpl_vars['aRow']['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aHistory']):
?>
			<div>
			 <?php echo $this->_tpl_vars['oContent']->getOrderStatus($this->_tpl_vars['aHistory']['order_status']); ?>
 - <?php echo $this->_tpl_vars['oLanguage']->getDateTime($this->_tpl_vars['aHistory']['post']); ?>
<br>
			<?php echo $this->_tpl_vars['aHistory']['comment']; ?>

			</div>
		<?php endforeach; endif; unset($_from); ?>

		<?php if ($this->_tpl_vars['aRow']['csc_post_date'] && ( $this->_tpl_vars['aAuthUser']['is_super_manager'] || $this->_tpl_vars['aAuthUser']['manager'] )): ?>
			<div><b>----</b></div>
			<div><b><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Sticker confirmed'); ?>
</b> <?php echo $this->_tpl_vars['aRow']['manager_name']; ?>
<br><?php echo $this->_tpl_vars['aRow']['csc_post_date']; ?>
</div>
			<div><b><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Box'); ?>
</b> <?php echo $this->_tpl_vars['aRow']['cpc_id_cart_packing_box']; ?>

			&nbsp;<b><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Sending'); ?>
</b> <?php echo $this->_tpl_vars['aBoxSending'][$this->_tpl_vars['aRow']['cpc_id_cart_packing_box']]; ?>
</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>	
		
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'cat_name'): ?>
<td><?php if ($this->_tpl_vars['aRow']['cat_name_changed']): ?><?php echo $this->_tpl_vars['aRow']['cat_name_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['cat_name']; ?>
<?php endif; ?></td>
<?php elseif ($this->_tpl_vars['sKey'] == 'code'): ?>
<td><?php if ($this->_tpl_vars['aRow']['code_changed']): ?><?php echo $this->_tpl_vars['aRow']['code_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['code']; ?>
<?php endif; ?><br><font color=red>ZZZ_<?php echo $this->_tpl_vars['aRow']['zzz_code']; ?>
</font></td>
<?php else: ?><td><?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>
</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>