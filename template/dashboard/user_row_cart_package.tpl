<td>{$aRow.code} <br>
{if $aRow.autopay}<br><font color=brown ><b>{$oLanguage->getMessage("Autopay")}</b></font>
{$oLanguage->getContextHint("cart_package_autopay_hint")}
{/if}
{if $aRow.full_payment}<br><font color=red ><b>{$oLanguage->getMessage("Full Payment")}</b></font>
{$oLanguage->getContextHint("cart_package_full_payment")}
{/if}
 </td>
<td>{$oLanguage->getOrderStatus($aRow.order_status)}
	{if $aRow.order_status=='pending'}{$oLanguage->getContextHint("pending_order_status",true)}{/if}
</td>
<td> {$oLanguage->PrintPrice($aRow.drr_ttl_price)}</td>
<td>{$aRow.name_customer}&nbsp;</td>
<td>{$aRow.customer_comment}
	{if $aRow.delivery_comment}
		&nbsp;{$aRow.delivery_comment}
	{/if}
</td>
<td>{$oLanguage->getDateTime($aRow.post)}</td>
<td>
	<a href="{strip}/?action=finance_bill_add&code_template=factura_bill
	&data[amount]={$oLanguage->Price($aRow.price_total,'UAH',true)}
	&data[id_cart_package]={$aRow.id}
	&data[login]={$aRow.user_login}
	{/strip}" 
	target="_blank"
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Invoice Bill")}</a>
<br>
</td>
