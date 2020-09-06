<?php /* Smarty version 2.6.18, created on 2019-09-25 12:40:00
         compiled from manager_panel/manager_package_list/form_order_search.tpl */ ?>
<ul class="order-list nav nav-pills" role="tablist" style="display: inline-block;float: left;">
    <li role="presentation" <?php if (! $this->_tpl_vars['search_order_status'] && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
    	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('all'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'new' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=new<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_new'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'in_wait' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=in_wait<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_in_wait'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'work' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=work<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_work'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'assembled' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=assembled<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_assembled'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'shipment' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=shipment<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_shipment'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'shipment_2' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=shipment_2<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_shipment_2'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'delivery' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=delivery<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_delivery'); ?>
</a>
    </li>    
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'cover' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=cover<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_cover'); ?>
</a>
    </li> 
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'end' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=end<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_end'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'return' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=return<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_return'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'refused' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=refused<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_refused'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_order_status'] == 'archive' && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_order_status=archive<?php if ($this->_tpl_vars['search_all_manager']): ?>&search_all_manager=1<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('menu_archive'); ?>
</a>
    </li>    
	<li>&nbsp;</li><li>&nbsp;</li>
    <li role="presentation" <?php if (! $this->_tpl_vars['search_all_manager'] && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
<?php if ($this->_tpl_vars['search_order_status']): ?>&search_order_status=<?php echo $this->_tpl_vars['search_order_status']; ?>
<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('only_own'); ?>
</a>
    </li>
    <li role="presentation" <?php if ($this->_tpl_vars['search_all_manager'] && ! $this->_tpl_vars['filtered']): ?>class="active"<?php endif; ?>>
       	<a href="/?action=<?php echo $this->_tpl_vars['sAction']; ?>
&search_all_manager=1<?php if ($this->_tpl_vars['search_order_status']): ?>&search_order_status=<?php echo $this->_tpl_vars['search_order_status']; ?>
<?php endif; ?>" onclick="xajax_process_browse_url(this.href); return false;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('all'); ?>
</a>
    </li>    
</ul>
<button type="button" class="btn btn-default btn-sm" style="margin-top: 6px;margin-left:2px;"
	onclick="xajax_process_browse_url('/?action=manager_panel_create_order'); return false;">
	<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?php echo $this->_tpl_vars['oLanguage']->getMessage('create package'); ?>

</button>
<div style="float:right;padding: 15px 20px 0 0;">
	<a href="<?php echo '/?action=manager_panel_manager_package_list_search'; ?><?php if ($this->_tpl_vars['search_all_manager']): ?><?php echo '&search_all_manager=1'; ?><?php endif; ?><?php echo ''; ?><?php if ($this->_tpl_vars['search_order_status']): ?><?php echo '&search_order_status='; ?><?php echo $this->_tpl_vars['search_order_status']; ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?>
"
		onclick="xajax_process_browse_url(this.href); return false;">
		<img src="/image/design/lupa2.png" style="cursor:pointer;">
	</a>
</div>