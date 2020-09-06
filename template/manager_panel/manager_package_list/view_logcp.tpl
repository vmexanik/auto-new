<div style="max-height:350px;min-height:30px;border: #ddd;padding:5px;">
	{if $aLogCP} 
	{foreach from=$aLogCP item=aItem}
		<div class="panel panel-default" style="margin-bottom: 5px;">
			<div style="padding:5px;">
				<span style="float:right">{$aItem.created}</span>
				<br>
				{if $aItem.order_status=='removed_cart'}
					<img src="/image/design/cancel.png" width=16 hspace="3" border="0" align="absmiddle">
					{$oLanguage->GetMessage('in cart package')} <span style="color:blue;cursor:pointer;"
					onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aItem.id_cart_package}&return=action%3Dmanager_panel_manager_package_list'); return false;">#{$aItem.id_cart_package}</span>
					{$oLanguage->GetMessage('removed cart item')}
				{else}
					<img src="/image/button_ok.png" width=16 hspace="3" border="0" align="absmiddle">
					{$oLanguage->GetMessage('cart package')} <span style="color:blue;cursor:pointer;"
					onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aItem.id_cart_package}&return=action%3Dmanager_panel_manager_package_list'); return false;">#{$aItem.id_cart_package}</span>
					{$aItem.name_status}
				{/if}
			</div>
		</div>
	{/foreach}
	{else}
		{$oLanguage->getMessage('no items found')}
	{/if}
</div>