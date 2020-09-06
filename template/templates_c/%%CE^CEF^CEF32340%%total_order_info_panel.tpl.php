<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/template/total_order_info_panel.tpl */ ?>
		   	<div class="col-lg-5">
		   		<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('total'); ?>
:</b>
		   		<b style="float:right"><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aCartPackage']['price_total_no_delivery']); ?>
</b>
		   		<br>
		   		<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('delivery price'); ?>
:</b>
		   		<b style="float:right"><?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aCartPackage']['price_delivery']); ?>
</b>
		   		<br><br>
		   		<b style="font-size:18px;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('subtotal'); ?>
:</b>
		   		<b style="font-size:18px;float:right"><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aCartPackage']['price_total']); ?>
</b>
		   		<br><br>
		   		<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Created'); ?>
:</b>
		   		<b style="float:right"><?php echo $this->_tpl_vars['oLanguage']->GetPostDate($this->_tpl_vars['aCartPackage']['post_date']); ?>
</b>
		   		<br>
		   		<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('Change'); ?>
:</b>
		   		<b style="float:right"><?php echo $this->_tpl_vars['oLanguage']->GetPostDate($this->_tpl_vars['aCartPackage']['change']); ?>
</b>
		    </div>