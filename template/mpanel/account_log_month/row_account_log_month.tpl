{if $aRow.amount_debit == 0 && $aRow.amount_credit == 0}
	{assign var="grey" value="1"}
{/if}
<td {if $grey}style="color:#bfbfbf;"{/if}>{$aRow.date_month|date_format:'%d.%m.%Y'}</td>
<td {if $grey}style="color:#bfbfbf;"{/if}>{$aRow.title}</td>
<td {if $grey}style="color:#bfbfbf;"{/if}>
	<font style="color:#aaa;">{$oLanguage->PrintCurrencyPrice($aRow.amount_debit_start,'USD')} / </font> 
	{$oLanguage->PrintCurrencyPrice($aRow.amount_currency_debit_start,$sCodeCurrency)}
</td>
<td {if $grey}style="color:#bfbfbf;"{/if}>
	<font style="color:#aaa;">{$oLanguage->PrintCurrencyPrice($aRow.amount_debit,'USD')} / </font> 
	{$oLanguage->PrintCurrencyPrice($aRow.amount_currency_debit,$sCodeCurrency)}
</td>
<td {if $grey}style="color:#bfbfbf;"{/if}>
	<font style="color:#aaa;">{$oLanguage->PrintCurrencyPrice($aRow.amount_credit,'USD')} / </font>  
	{$oLanguage->PrintCurrencyPrice($aRow.amount_currency_credit,$sCodeCurrency)}
</td>
<td {if $grey}style="color:#bfbfbf;"{/if}>
	<font style="color:#aaa;">{$oLanguage->PrintCurrencyPrice($aRow.amount_debit_end,'USD')} / </font> 
	{$oLanguage->PrintCurrencyPrice($aRow.amount_currency_debit_end,$sCodeCurrency)}
</td>