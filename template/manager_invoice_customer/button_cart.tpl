<p>
<b>{$oLanguage->GetMessage('Additional invoice payments')}:</b><br>

{foreach from=$aAdditionalAccountLog item=aItem}
	<input type=checkbox name=data[additional_account_log][] value='{$aItem.id}' checked> <b>${$aItem.amount}</b>
	{$aItem.post_date} <i>{$aItem.description}</i><br>
{/foreach}
<br>

<b>{$oLanguage->GetMessage('Account')}:</b>&nbsp;&nbsp;
{html_options name=invoice_customer[id_account] options=$aAccount selected=$aActiveAccount.id}

<textarea name=invoice_customer[comment] rows="3" cols="70" placeholder="комментарий по доставке">{$sAutoComment}</textarea><br><br>
</p>

<input type=button class='at-btn' value="{$oLanguage->getMessage("Create Customer Invoice")}"
	onclick="if (confirm('{$oLanguage->getMessage("Create Customer Invoice?")}'))
	 mt.ChangeActionSubmit(this.form,'manager_invoice_customer_create');">

<input type=button class='at-btn' value="{$oLanguage->getMessage("Create Customer Invoice and Print")}"
	onclick="if (confirm('{$oLanguage->getMessage("Create Customer Invoice?")}'))
	 mt.ChangeActionSubmit(this.form,'manager_invoice_customer_create_print');">

<input type=button class='at-btn' value="{$oLanguage->getMessage("Return to user list")}"
	onclick="location.href='/?action=manager_invoice_customer'">


</td>

</tr>
</table>

<input type=hidden name=is_post value='1'>
<input type=hidden name=invoice_customer[id_user] value='{$smarty.request.id_user}'>