{if $aAuthUser.type_=='manager' || $aAuthUser.price_type=='margin'}

<form method="GET" id="id_form_change_currency" name="id_form_change_currency" style="display: none">
<table width="300" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap>{$oLanguage->GetMessage('Print the invoice in')}:&nbsp;</td>
    <td><select name=id_currency id=id_currency style="width: 120px;"
	 onchange="document.id_form_change_currency.submit();">
	 {html_options options=$aCurrency selected=$iCurrencyDefault}
	 </select></td>
	 <td align="left"><!--input type="button" value="{$oLanguage->GetMessage('Change the essentials')}"
		onclick="
		document.getElementById('id_button_test').style.display='block';
		var obj = document.getElementById('id_text_area_essentials');
		obj.readOnly=false;
		obj.style.borderStyle='solid';
		obj.focus();

	  "--></td>
  </tr>
</table>

{if $aAuthUser.type_=='manager'}
	{assign var='sSubmitAction' value='manager_invoice_customer_print'}
{else}
	{assign var='sSubmitAction' value='invoice_account_log_invoice_print'}
{/if}


<input type="hidden" name="action" value="{$sSubmitAction}">
<input type="hidden" name="id" value="{$aCustomerInvoice.id}">
</form>
{/if}