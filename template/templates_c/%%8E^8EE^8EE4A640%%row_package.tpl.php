<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:05
         compiled from manager/row_package.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manager/row_package.tpl', 3, false),array('modifier', 'cat', 'manager/row_package.tpl', 27, false),)), $this); ?>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('#'); ?>
</div>
    <a href="/?action=manager_package_edit&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo $this->_tpl_vars['aRow']['id']; ?>
</a>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('date'); ?>
</div>
    <?php echo $this->_tpl_vars['oLanguage']->getPostDateTime($this->_tpl_vars['aRow']['post_date']); ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('order status'); ?>
</div>
    <?php echo $this->_tpl_vars['oLanguage']->getOrderStatus($this->_tpl_vars['aRow']['order_status']); ?>
 <?php if ($this->_tpl_vars['aRow']['is_reclamation']): ?><b><?php echo $this->_tpl_vars['oLanguage']->getMessage('reclamation'); ?>
</b><?php endif; ?>
    <?php if ($this->_tpl_vars['aRow']['is_need_check']): ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('need check'); ?>
</div>
    	<span id="auto_<?php echo $this->_tpl_vars['aRow']['id']; ?>
" 
        	onclick="set_checked_auto(this,<?php if (( $this->_tpl_vars['aRow']['is_checked_auto'] )): ?>'0'<?php else: ?>'1'<?php endif; ?>)" 
        	onmouseover="$('#tip_auto_<?php echo $this->_tpl_vars['aRow']['id']; ?>
').show();" 
        	onmouseout="$('#tip_auto_<?php echo $this->_tpl_vars['aRow']['id']; ?>
').hide();">
        	<?php if ($this->_tpl_vars['aRow']['is_checked_auto'] == 0): ?>
        		<a><img src="/image/design/not_sel_chk.png"></img></a>
        	<?php else: ?>
        		<a><img src="/image/design/sel_chk.png"></img></a>
        	<?php endif; ?>
        	<div align="left" style="width: 500px;" class="tip_div" id="tip_auto_<?php echo $this->_tpl_vars['aRow']['id']; ?>
"><?php echo $this->_tpl_vars['aRow']['sAutoInfo']; ?>
</div>
    	</span>
    <?php endif; ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('customer'); ?>
</div>
    <?php $this->assign('Id', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['aRow']['id_user'])) ? $this->_run_mod_handler('cat', true, $_tmp, '_') : smarty_modifier_cat($_tmp, '_')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['aRow']['id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['aRow']['id']))); ?>
    <?php echo $this->_tpl_vars['oLanguage']->AddOldParser('customer_uniq',$this->_tpl_vars['Id']); ?>

</td>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('parts'); ?>
</div>
    <?php if ($this->_tpl_vars['aRow']['aCart']): ?>
    <?php $_from = $this->_tpl_vars['aRow']['aCart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iCart'] => $this->_tpl_vars['aCart']):
?>
    <?php if ($this->_tpl_vars['aCart']['history']): ?>
    <nobr>
    <strong><a href="#" onmouseover="show_hide('history_<?php echo $this->_tpl_vars['aCart']['id']; ?>
','inline')" onmouseout="show_hide('history_<?php echo $this->_tpl_vars['aCart']['id']; ?>
','none')"
    	onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
    	</a></strong></nobr>
    <div style="display: none; " align=left class="tip_div" id="history_<?php echo $this->_tpl_vars['aCart']['id']; ?>
">
    	<?php $_from = $this->_tpl_vars['aCart']['history']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aHistory']):
?>
    		<div>
    		 <?php echo $this->_tpl_vars['oLanguage']->getOrderStatus($this->_tpl_vars['aHistory']['order_status']); ?>
 - <?php echo $this->_tpl_vars['oLanguage']->getDateTime($this->_tpl_vars['aHistory']['post']); ?>
<br>
    		<?php echo $this->_tpl_vars['aHistory']['comment']; ?>

    		</div>
    	<?php endforeach; endif; unset($_from); ?>
    </div>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['aCart']['order_status'] == 'refused'): ?><strike><?php endif; ?>
    <?php echo $this->_tpl_vars['aCart']['code']; ?>
 <?php if ($this->_tpl_vars['aCart']['code_changed']): ?>=>(<?php echo $this->_tpl_vars['aCart']['code_changed']; ?>
)<?php endif; ?> <b><?php if ($this->_tpl_vars['aCart']['cat_name_changed']): ?><?php echo $this->_tpl_vars['aCart']['cat_name_changed']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aCart']['cat_name']; ?>
<?php endif; ?>
    	[ <font color="red"><?php echo $this->_tpl_vars['aCart']['number']; ?>
</font> ] </b><font color=green><?php echo $this->_tpl_vars['aCart']['name_translate']; ?>
</font>
    <?php if ($this->_tpl_vars['aCart']['order_status'] == 'refused'): ?></strike><?php endif; ?>
    <br>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['aRow']['order_status'] == 'pending' || $this->_tpl_vars['aRow']['order_status'] == 'work' && $this->_tpl_vars['aRow']['is_payed'] == 0): ?>
    <a href="/?action=manager_empty_package_delete&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&return=<?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
    		onclick="if (!confirm('<?php echo $this->_tpl_vars['oLanguage']->getMessage("Are you sure?"); ?>
')) return false;"
    		><img src="/image/delete.png" border=0 width=16 align=absmiddle /> <?php echo $this->_tpl_vars['oLanguage']->getMessage('Delete Package'); ?>
</a>
    <?php endif; ?>
</td>
<td>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('total without delivery'); ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total']-$this->_tpl_vars['aRow']['price_delivery']); ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('delivery price'); ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_delivery']); ?>

    <?php if ($this->_tpl_vars['aRow']['price_additional'] > 0): ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Additional payment'); ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_additional']); ?>

    <?php endif; ?>
    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('total'); ?>
</div>
    <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total']); ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('delivery type'); ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['delivery_type_name']; ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('payment type'); ?>
</div>
    <?php echo $this->_tpl_vars['aRow']['payment_type_name']; ?>

    <br>
    <div class="order-num"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Is payed'); ?>
</div>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'addon/mpanel/yes_no.tpl', 'smarty_include_vars' => array('bData' => $this->_tpl_vars['aRow']['is_payed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
<tr>
    <td colspan="8" style="border-bottom: 3px double #2f86c2; text-align: center;">
    <?php if ($this->_tpl_vars['aAuthUser']['is_super_manager']): ?>
    	<?php if ($this->_tpl_vars['aRow']['order_status'] == 'pending' || $this->_tpl_vars['aRow']['order_status'] == 'new' || $this->_tpl_vars['aRow']['order_status'] == ''): ?>
    	<a href="/?action=manager_package_order&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&confirm=1"
    		><img src="/image/apply.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('Send Package to Work'); ?>
</a>
    	<?php endif; ?>
    <?php endif; ?>
    <a href="/?action=manager_order_print&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&id_user=<?php echo $this->_tpl_vars['aRow']['id_user']; ?>
" target=_blank
    	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/><?php echo $this->_tpl_vars['oLanguage']->getMessage('Print'); ?>
</a>

	<a href="/?action=manager_order&search[id_cart_package]=<?php echo $this->_tpl_vars['aRow']['id']; ?>
"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php if ($this->_tpl_vars['aRow']['is_viewed'] == 0): ?><b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Browse Detals'); ?>
</b><?php else: ?><?php echo $this->_tpl_vars['oLanguage']->getMessage('Browse Detals'); ?>
<?php endif; ?></a>
    
    <a href="<?php echo '/?action=finance_bill_add&code_template=order_bill&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[login]='; ?><?php echo $this->_tpl_vars['aRow']['login']; ?><?php echo '&return_action=manager_package_list'; ?>
"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('Order Bill'); ?>
</a>
    <a href="<?php echo '/?action=finance_bill_add&code_template=order_bill_bv&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[login]='; ?><?php echo $this->_tpl_vars['aRow']['login']; ?><?php echo '&return_action=manager_package_list'; ?>
"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('Order Bill BV'); ?>
</a>
    <a href="<?php echo '/?action=finance_bill_add&code_template=order_bill_rko&data[amount]='; ?><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price_total'],'',2,'<none>'); ?><?php echo '&data[id_cart_package]='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&data[login]='; ?><?php echo $this->_tpl_vars['aRow']['login']; ?><?php echo '&return_action=manager_package_list'; ?>
"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('Order Bill RKO'); ?>
</a>
    <?php if (! $this->_tpl_vars['aRow']['is_payed']): ?>
    <a <?php echo 'href="/?action=manager_package_payed&id='; ?><?php echo $this->_tpl_vars['aRow']['id']; ?><?php echo '&return='; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['sReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?><?php echo '"'; ?>
><img src="/image/inbox.png" border=0 width=16 align=absmiddle /><?php echo $this->_tpl_vars['oLanguage']->getMessage('Set cart package payed'); ?>
</a>
    <?php endif; ?>
    </td>