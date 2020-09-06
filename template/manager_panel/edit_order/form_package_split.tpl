{include file=manager_panel/template/right_order_panel.tpl}
<div class="col-sm-8" style="padding-left:0;">
	{include file=manager_panel/template/right_order_menu.tpl}
	<button type="button" class="btn btn-default" style="float:right;margin-right:20px;"
		onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aCartPackage.id}&return={$sReturn|escape:"url"}'); return false;">
		{$oLanguage->getMessage('cancel')}
	</button>
	<button type="button" class="btn btn-info" style="float:right;margin-right: 5px;" onclick="return split_manager_panel_order({$aCartPackage.id});">
		<img src="/image/design/unlink.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('split_order')}" title="{$oLanguage->getMessage('split_order')}">
		{$oLanguage->getMessage('split_order')}
	</button>
	
	{include file=manager_panel/template/subtotal_order_info.tpl}
</div>
<input type="hidden" id="cart_items" value="{$iCartItems}">