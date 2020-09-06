<table width=100% border=0>
	<tr>
		<td><b>{$oLanguage->GetMessage("paypal amount")}:</b></td>
		<td>
<input type="text" name="amount"
	value="{if $smarty.request.amount}{$smarty.request.amount}{else}{$oLanguage->GetConstant('payment:default_amount','0.6')}{/if}">
{html_options name=currency_code options=$aPaypalCurrency style='width:130px'}

<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business"value="{$oLanguage->GetConstant('payment:paypal_email','mstarrr@gmail.com')}">
<input type="hidden" name="item_name" value="{$oLanguage->GetConstant('payment:paypal_item_name','Paypal Item Name')}">
<input type="hidden" name="item_number" value="{$aAuthUser.id}">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="return" value="http://{$SERVER_NAME}/?action=payment_paypal_success">
<input type="hidden" name="cancel_return" value="http://{$SERVER_NAME}/?action=payment_paypal_fail">
<input type="hidden" name="notify_url" value="http://{$SERVER_NAME}/?action=payment_paypal_result">
<input type="hidden" name="custom" value="{$oLanguage->GetConstant('payment:paypal_custom','Custom')}">
<input type="hidden" name="rm" value="2">
		</td>
	</tr>

</table>