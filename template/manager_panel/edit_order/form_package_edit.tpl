{include file=manager_panel/template/right_order_panel.tpl}
<div class="col-sm-8" style="padding-left:0;">
	<button type="button" class="btn btn-default btn-sm" style="float:left;"
		onclick="xajax_process_browse_url('/?action=manager_panel_edit_order_add&id={$aCartPackage.id}&return={$sReturn|escape:"url"}'); return false;">
		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {$oLanguage->getMessage('add to order')}
	</button>
	{include file=manager_panel/template/right_order_menu.tpl}
	<button type="button" class="btn btn-default" style="float:right;margin-right:20px;"
		onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list_view&id={$aCartPackage.id}&return={$sReturn|escape:"url"}'); return false;">
		{$oLanguage->getMessage('cancel')}
	</button>
	<button type="button" class="btn btn-success" style="float:right;margin-right: 5px;" onclick="return save_manager_panel_order({$aCartPackage.id});">
		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> {$oLanguage->getMessage('apply')}
	</button>

	{include file=manager_panel/template/subtotal_order_info.tpl}
</div>