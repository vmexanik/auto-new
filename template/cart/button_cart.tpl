<table border=0 width=99%>
<tr><td width=70%>
{if $aItem}
<input type=button class='at-btn order-package-at-btn' value="{$oLanguage->getMessage("Order Package")}"
	onclick="javascript: location.href='/?action=cart_onepage_order'" />
	
<input type=button class='at-btn order-package-at-btn' value="{$oLanguage->getMessage("Clear cart")}"
	onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to clar cart?")}')) location.href='/pages/cart_cart_clear'" />

<span style="float: right;">
<a href='/?action=additional_delivery' target='_blank'>{$oLanguage->GetMessage('Delivery and Garanties')}</a>&nbsp;&nbsp;&nbsp;

111<a href='/?action=cart_cart_print' target='_blank'><img src='/image/fileprint.png'  border='0' hspace='2' align='absmiddle'
	/> {$oLanguage->GetMessage('Print')}</a>
</span>

{/if}
</td>
</tr>
</table>
{include file="cart/order_by_phone.tpl"}

<br>
<br>
