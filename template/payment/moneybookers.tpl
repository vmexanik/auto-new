<table width=100% border=0>
	<tr>
		<td><b>{$oLanguage->GetMessage("moneybookers amount")}:</b></td>
		<td>
<input type="text" name="amount"
	value="{if $smarty.request.amount}{$smarty.request.amount}{else}{$oLanguage->GetConstant('payment:default_amount','0.6')}{/if}">
{html_options name=currency options=$aMoneyBookersCurrency style='width:130px'}


<input type="hidden" name="pay_to_email" value="{$oLanguage->GetConstant('moneybookers_email','mstar@partmaster.com.ua')}">
<input type="hidden" name="return_url" value="http://partmaster.com.ua/?action=payment_moneybookers_success">
<input type="hidden" name="return_url_target" value="1">
<input type="hidden" name="cancel_url" value="http://partmaster.com.ua/?action=payment_moneybookers_fail">
<input type="hidden" name="cancel_url_target" value="1">
<input type="hidden" name="status_url" value="http://partmaster.com.ua/?action=payment_moneybookers_result">
<input type="hidden" name="status_url2" value="mailto:mikestar@yandex.ru">
<input type="hidden" name="dynamic_descriptor" value="Descriptor">
<input type="hidden" name="language" value="RU">
<input type="hidden" name="confirmation_note"
value="{$oLanguage->GetMessage("Money will be soon deposited to your account!")}" >

<input type="hidden" name="detail1_description" value="{$oLanguage->GetMessage("Deposit to user with id:")}">
<input type="hidden" name="detail1_text" value="{$aAuthUser.id} - {$aAuthUser.login}">
<input type="hidden" name="payment_methods" value="LSR,MAE,JCB,DIN,AMX,VSE,VSD,MSC,ACC,PWY36,PWY19,PWY15,WLT,VSA,">

<input type="hidden" name="transaction_id" value="{$sTransactionId}">

<input type="hidden" name="merchant_fields" value="payedto_id_user">
<input type="hidden" name="payedto_id_user" value="{$aAuthUser.id}">


		</td>
	</tr>

</table>