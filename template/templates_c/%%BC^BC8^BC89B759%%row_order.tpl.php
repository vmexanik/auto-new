<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:33
         compiled from manager/row_order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager/row_order.tpl', 5, false),array('modifier', 'cat', 'manager/row_order.tpl', 28, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['oTable']->aColumn; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['item']):
?>
<?php if ($this->_tpl_vars['sKey'] == 'action'): ?>
<td nowrap>
	<?php if ($this->_tpl_vars['aRow']['order_status'] == 'pending'): ?>
		<a href="/?action=manager_order_refuse_pending&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
			onclick="if (!confirm('<?php echo $this->_tpl_vars['oLanguage']->getMessage("Are you sure?"); ?>
')) return false;"
			><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Refuse pending'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Refuse pending'); ?>
" /> </a>

	<?php else: ?>
		<?php if ($this->_tpl_vars['aAuthUser']['is_super_manager']): ?>
	<a href="/?action=manager_order_edit&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Status'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Status'); ?>
" /></a>
		<?php endif; ?>
	<?php endif; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'id_cart_package'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <a href="/?action=manager_package_edit&id=<?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?>
&return=action%3Dmanager_package_list"><?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?>
</a>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('date'); ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['post_date']; ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('detail id'); ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['id']; ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php $this->assign('Id', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['aRow']['id_user'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aRow']['id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aRow']['id']))); ?>
    <?php echo $this->_tpl_vars['oLanguage']->AddOldParser('customer_uniq',$this->_tpl_vars['Id']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'name'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('name'); ?>
</div>
    <span>
    	<a href="/?action=manager_edit_weight&id_cart=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&item_code=<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
&name=<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
    	><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
    	<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>

    	<br><font color="#9B9B9B"><?php echo $this->_tpl_vars['aRow']['customer_comment']; ?>
</font>
    </span>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('order status'); ?>
</div>
	<?php echo $this->_tpl_vars['oContent']->getOrderStatus($this->_tpl_vars['aRow']['order_status']); ?>

	<?php if ($this->_tpl_vars['aRow']['history']): ?>
	<br><nobr>
	<strong><a href="#" onmouseover="show_hide('history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
','inline')" onmouseout="show_hide('history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
','none')"
		onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
		<?php echo $this->_tpl_vars['oLanguage']->getMessage('History'); ?>
</a>&nbsp;</strong></nobr>
	<div style="display: none; " align=left class="status_div" id="history_<?php echo $this->_tpl_vars['aRow']['id']; ?>
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
<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
		<br><br>
					<a href="<?php echo '/?action=finance_bill_provider_add&code_template=order_bill_rko&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_real'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?><?php echo '&data[id_cart]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[id_provider]='; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '&return_action=manager_order'; ?>
"
				><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('RKO'); ?>
</a>
			<br>
			<a href="<?php echo '/?action=finance_bill_provider_add&code_template=order_bill_bv&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_real'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?><?php echo '&data[id_cart]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[id_provider]='; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '&return_action=manager_order'; ?>
"
				><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('BV'); ?>
</a>
			<br>
			<a href="<?php echo '/?action=finance_bill_provider_add&code_template=order_bill&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_real'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id_cart_package']; ?><?php echo '&data[id_cart]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[id_provider]='; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '&return_action=manager_order'; ?>
"
			><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('PKO'); ?>
</a>
			<?php endif; ?>	
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'provider'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
	<a href="/?action=manager_change_provider&id_cart=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
	<a href="/?action=manager_order&search[id_provider]=<?php echo $this->_tpl_vars['aRow']['id_provider_ordered']; ?>
"><?php echo $this->_tpl_vars['aRow']['provider_name_ordered']; ?>
</a>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'user'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php $this->assign('Id', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['aRow']['id_user'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aRow']['id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aRow']['id']))); ?>
    <?php echo $this->_tpl_vars['oLanguage']->AddOldParser('customer_uniq',$this->_tpl_vars['Id']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'date'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['oLanguage']->getDateTime($this->_tpl_vars['aRow']['post_date']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'term'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['term']; ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'buh_balance'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <a href='/?action=buh_changeling&search[id_buh]=361&search[id_subconto1]=<?php echo $this->_tpl_vars['aRow']['id_user']; ?>
' target=_blank>
	<font color="red"><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['buh_balance']); ?>
</font></a>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'debt'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php if ($this->_tpl_vars['aRow']['buh_balance'] < $this->_tpl_vars['aRow']['total']): ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total']-$this->_tpl_vars['aRow']['buh_balance']); ?>
<?php else: ?>0<?php endif; ?>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'total_original'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_original']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'total_profit'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total_profit']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'price'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
	<?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price']); ?>
<br>
	<font color=blue><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aRow']['price_original'],$this->_tpl_vars['aRow']['id_currency_provider']); ?>
</font>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'total'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['number']; ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('total'); ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aRow']['total']); ?>

</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'cat_name'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('brand'); ?>
</div>
    <?php if ($this->_tpl_vars['aRow']['cat_name_changed']): ?><?php echo $this->_tpl_vars['aRow']['cat_name_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['cat_name']; ?>
<?php endif; ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('code'); ?>
</div>
    <?php if ($this->_tpl_vars['aRow']['code_changed']): ?><?php echo $this->_tpl_vars['aRow']['code_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aRow']['code']; ?>
<?php endif; ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('zzz_code'); ?>
</div>
    <font color=red>ZZZ_<?php echo $this->_tpl_vars['aRow']['zzz_code']; ?>
</font>
</td>
<?php elseif ($this->_tpl_vars['sKey'] == 'number'): ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>

    <?php if ($this->_tpl_vars['aRow']['number'] > 1): ?><a href="/?action=manager_separate_cart&id_cart=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a><?php endif; ?>
</td>
<?php else: ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['item']['sTitle']; ?>
</div>
    <?php echo $this->_tpl_vars['aRow'][$this->_tpl_vars['sKey']]; ?>

</td>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>