<td>
    <div class="order-num">{$oLanguage->GetMessage('ID')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Order Status')}</div>
    {$oLanguage->getOrderStatus($aRow.order_status)}
	{if $aRow.order_status=='pending'}{$oLanguage->getContextHint("pending_order_status",true)}{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Total')}</div>
    {$oCurrency->PrintPrice($aRow.price_total,1)}<br>
    <div class="order-num">{$oLanguage->GetMessage('delivery type')}</div>
    {$aRow.delivery_type_name}<br>
    <div class="order-num">{$oLanguage->GetMessage('payment type')}</div>
    {$aRow.payment_type_name}<br>
    
    {if $aRow.id_payment_type == 1}
    <nobr><br><a href="/?action=cart_payment_end_button&data[id_payment_type]={$aRow.id_payment_type}&id_cart_package={$aRow.id}"
    	><img src="/image/payment/money.png" border=0 width=16 align=absmiddle hspace=1
    	/>{$oLanguage->getMessage("Pay for cart package")}</a></nobr>
    {/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Comment')}</div>
    {$aRow.customer_comment}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Number declaration')}</div>
    {if $aRow.number_declaration}<a href="/?action=payment_declaration&id={$aRow.id}">{$aRow.number_declaration}&nbsp;</a>{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$oLanguage->GetPostDate($aRow.post_date)}
</td>
<td nowrap>
<nobr><a href="/?action=cart_package_print&id={$aRow.id}" target=_blank
	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Print")}</a></nobr>

<nobr><a href="/?action=cart_order&search[id_cart_package]={$aRow.id}"
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Browse Order Items")}</a></nobr>
</nobr>

<br>
{if $aRow.order_status=='pending'}
<nobr><a href="/?action=cart_package_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("EEdit")}</a>
</nobr>
{/if}

{if $aRow.order_status=='pending' && !$aRow.is_confirm}
<a href="/?action=cart_package_delete&id={$aRow.id}"
	><img src="/image/delete.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Delete and return cart")}</a>
<br>
{/if}

</td>