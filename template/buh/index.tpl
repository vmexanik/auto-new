<h1>{$oLanguage->GetMessage('Module Buh')}</h1>
{*<a href="/?action=buh_changeling&search[date_from]={$date_from}&search[date_to]={$date_to}&return={$sReturn|escape:"url"}">{$oLanguage->GetMessage("Changeling")} </a>
<br>
<br>
<a href="/?action=buh_add_amount&search[id_buh_debit]=311&search[id_buh_credit]=361&return={$sReturn|escape:"url"}">{$oLanguage->GetMessage("Add amount")} </a>
<br>
<br>*}
<table cellpadding="0" cellspacing="0" border="0" width="50%">
	<tr>
		<td height="28">
			{$oLanguage->GetMessage("Month can be closed only after verified all billings and received payments for the current period.")}
		</td>
	</tr>
</table>
<a href="/?action=buh_close_month&return={$sReturn|escape:"url"}"
onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to close current period?")}')) return false;"
>{$oLanguage->GetMessage("Close curent period")} 
{$oLanguage->GetConstant('buh:current_period')}</a>

