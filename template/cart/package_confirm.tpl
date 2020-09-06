<FORM method=post>

<H3>{$oLanguage->getMessage("Cart Package Confirm")}</h3>
<table width="99%" cellspacing=0 cellpadding=5 class="datatable">

<tr>
	<th width=10%><nobr>{$oLanguage->getMessage("CatName")}</th>
	<th width=10%><nobr>{$oLanguage->getMessage("CartCode")}</th>
	<th width=60%><nobr>{$oLanguage->getMessage("Name")}</th>
	<th><nobr>{$oLanguage->getMessage("Number")}</th>
	<th><nobr>{$oLanguage->getMessage("Price")}</th>
	<th><nobr>{$oLanguage->getMessage("Total")}</th>

</tr>
{foreach item=aItem from=$aUserCart}
<tr class="{cycle values="even,none"}">
	<td>{$aItem.cat_name}</td>
	<td>{if $aItem.code_visible}
		{$aItem.code}
	{else}
		<i>{$oLanguage->getMessage("cart_invisible")}</i>
	{/if}</td>
	<td><div style="width:560px;overflow:overlay;">
	    {$oContent->PrintPartName($aItem)}
	    </div>
	</td>
	<td>{$aItem.number}</td>
	<td>{$oCurrency->PrintPrice($aItem.price)}</td>
	<td>{$oCurrency->PrintSymbol($aItem.number_price)}</td>
</tr>
{/foreach}
<tr>
	<td colspan=6><hr></td>
</tr>

<tr>
	<td colspan=5 align=right>{$oLanguage->getMessage('Subtotal')}:</td>
	<td>{$oCurrency->PrintSymbol($dSubtotal)}</td>
</tr>
<tr>
	<td align=left colspan=3>
	{foreach from=$aDeliveryType item=aItem}
		<input type="radio" name="id_delivery_type" value="1"
		{if $smarty.session.current_cart.id_delivery_type==$aItem.id } checked="checked"{/if}
		onclick="{strip}
		xajax_process_browse_url('/?action=delivery_set&xajax_request=1
			&id_delivery_type={$aItem.id}
			');
		{/strip}">
		{$aItem.name}

		&nbsp;&nbsp;&nbsp;
	{/foreach}

	</td>
	<td colspan=2 align=right>{$oLanguage->getMessage('Shipment Included')}:</td>
	<td><span id='price_delivery'>{$oCurrency->PrintPrice($smarty.session.current_cart.price_delivery)}</span></td>
</tr>

<tr>
	<td colspan=5 align=right><b>{$oLanguage->getMessage('Total')}</b>:</td>
	<td><b><span id='price_total'>{$oCurrency->PrintSymbol($dTotal)}</span></b></td>
</tr>

</table>



<div style="padding:5px 0 0 0;">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Cart Package Confirm")}"
	onclick="javascript: location.href='/?action=cart_check_account'">

<span style="float: right;">
<a href='/?action=additional_delivery' target='_blank'>{$oLanguage->GetMessage('Delivery and Garanties')}</a>&nbsp;&nbsp;&nbsp;
</span>

<input type=hidden name=action value=''>
<input type=hidden name=section value='work'>
<input type=hidden name=id value='{$aCartPackage.id}'>
<input type=hidden name=is_post value='1'>
<input type=hidden name=return_action value='cart_cart'>
</div>


</FORM>