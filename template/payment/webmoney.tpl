<table width=100% border=0>
	<tr>
		<td><b>{$oLanguage->GetMessage("webmoney amount")}:</b></td>
		<td>
<input type="text" name="LMI_PAYMENT_AMOUNT"
	value="{if $smarty.request.amount}{$smarty.request.amount}{else}{$oLanguage->GetConstant('payment:default_amount','0.6')}{/if}">

{html_options name=LMI_PAYEE_PURSE options=$aWebmoneyPurse style='width:130px'}

		</td>
	</tr>

<input type="hidden" name="LMI_PAYMENT_DESC" value="{$oLanguage->GetMessage('LMI_PAYMENT_DESC')}:{$aAuthUser.login}">
<input type="hidden" name="LMI_PAYMENT_NO" value="{$LMI_PAYMENT_NO}">
<input type="hidden" name="LMI_SIM_MODE" value="0">
<input type="hidden" name="id_user" value="{$aAuthUser.id}">

</table>