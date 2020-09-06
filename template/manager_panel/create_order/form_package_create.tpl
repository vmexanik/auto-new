{include file=manager_panel/create_order/right_order_panel.tpl}
<div class="col-sm-8" style="padding-left:0;">
	<button type="button" class="btn btn-default btn-sm {if !$aUser}disabled{/if}" style="float:left;"
		{if !$aUser}
			onclick="alert('{$oLanguage->getMessage('need select user')}');return false;"
		{else}
			onclick="xajax_process_browse_url('/?action=manager_panel_create_order_add&return={$sReturn|escape:"url"}'); return false;"
		{/if}>
		<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {$oLanguage->getMessage('add products')}
	</button>
	<button type="button" class="btn btn-danger btn-sm" style="float:right;"
		onclick="xajax_process_browse_url('/?action=manager_panel_create_order_clear&return={$sReturn|escape:"url"}'); return false;">
		<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> {$oLanguage->getMessage('clear cart')}
	</button>
	<div id="table_error" style="clear:both;padding-top:5px;"></div>
	{$sOrderItems}
	<br>
	<div class="col-lg-5" style="float:right;">
		<button style="float:right;" type="button" class="btn btn-lg btn-success {if !$aUser || !$iCartItems}disabled{/if}"
			{if !$aUser}
				onclick="alert('{$oLanguage->getMessage('need select user')}');return false;"
			{elseif !$iCartItems}
				onclick="alert('{$oLanguage->getMessage('need added products')}');return false;"
			{else}
				onclick="xajax_process_browse_url('/?action=manager_panel_create_order_create&return={$sReturn|escape:"url"}'); return false;"
			{/if}>{$oLanguage->getMessage('create package')}</button>
		<div style="clear:both;line-height: 25px;padding-top: 10px;">
		<b style="float:right;">{$oLanguage->getMessage('s_profit')}: {if $dOrderSubtotalProfit}{$dOrderSubtotalProfit}{/if}</b><br>
		<b style="float:right;">{$oLanguage->getMessage('total')}: {if $dOrderSubtotal}{$dOrderSubtotal-$iDeliveryPrice}{/if}</b><br>
		<b style="float:right;">{$oLanguage->getMessage('delivery price')}: {if $iDeliveryPrice}{$iDeliveryPrice}{/if}</b><br>
		<br>
		<b style="font-size:18px;float:right;">{$oLanguage->getMessage('subtotal')}: {if $dOrderSubtotal}{$dOrderSubtotal}{/if}</b><br>
		</div>
	</div>
</div>
