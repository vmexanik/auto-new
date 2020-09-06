<form action="{$smarty.server.REQUEST_URI}&is_post=1" method="post">
	<b>{$oLanguage->getMessage('choose post method')}:</b><br>
	{foreach from=$aDeliveryType item=aItem}
		<label><nobr><input type="radio" name="id_delivery_type" value="1"
		{if $smarty.session.current_cart.id_delivery_type==$aItem.id } checked="checked"{/if}
		onclick="{strip}
		xajax_process_browse_url('/?action=delivery_set&xajax_request=1
			&id_delivery_type={$aItem.id}
			');
		{/strip}">
		{$aItem.name}</nobr></label>
		&nbsp;&nbsp;&nbsp;
	{/foreach}
	<span id='region_delivery'>{$sDeliveryRegionSelector}</span><br><br>
	{if !$bAutopartmaster}
	<label><nobr>{$oLanguage->getMessage('delivery recipient')}: <input style="width:400px;" type="text" name="delivery_recipient" 
	value="{if $smarty.session.current_cart.delivery_recipient}{$smarty.session.current_cart.delivery_recipient}{/if}"
	></nobr></label>
	{/if}
	<input type=hidden value="1">
	<br /><input type=submit class='at-btn' value="{$oLanguage->getMessage("Apply")}">
</form>