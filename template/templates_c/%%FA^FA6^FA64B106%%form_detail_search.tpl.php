<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:07
         compiled from manager_panel/purchase/form_detail_search.tpl */ ?>
<div>
	<a href="#" onclick="xajax_process_browse_url('/?action=manager_panel_purchase_filter'); return false;" style="cursor:pointer">
		<span class="glyphicon glyphicon-filter" aria-hidden="true"></span><?php echo $this->_tpl_vars['oLanguage']->getMessage('filter'); ?>

	</a>
	<?php if ($this->_tpl_vars['sTextFilter']): ?>
		<div style="display: inline-block;padding-left: 20px;color: grey;">
			<?php echo $this->_tpl_vars['sTextFilter']; ?>

		</div>
	<?php endif; ?>
</div>