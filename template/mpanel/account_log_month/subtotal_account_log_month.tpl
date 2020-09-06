<tr>
	<td colspan=10> &nbsp;</td>
</tr>
<tr class="info_subtotal">
	<td colspan=3 align=right> {$oLanguage->getDMessage('Subtotal')}:</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.iAtStart,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.iAtStartCurrency,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.sum_ad,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.sum_currency_ad,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.sum_ac,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.sum_currency_ac,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.iAtEnd,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.iAtEndCurrency,$sCodeCurrency)}</b>
	</td>
</tr>
<tr>
	<td colspan=10> &nbsp;</td>
</tr>

<tr class="info_subtotal">
	<td colspan=2 align=right>
		<a href="?action=user_account_log&search=id_account[]={
			$aSubcontal.sIdAccount}%26date_from={$smarty.now|date_format:'%d.%m.%Y'
			}%26date_to={$smarty.now+86400|date_format:'%d.%m.%Y'}"
			onclick="xajax_process_browse_url(this.href); return false;">
			<img border=0 src="/libp/mpanel/images/small/document_refresh.png"  hspace=3 align=absmiddle/>
			{$oLanguage->getDMessage('Search UAL')}
		</a>&nbsp;
	</td>
	<td align=right> {$oLanguage->getDMessage('Today')}:</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayStart,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayStartCurrency,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayDebit,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayDebitCurrency,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayCredit,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayCreditCurrency,$sCodeCurrency)}</b>
	</td>
	<td>
		<font style="color:grey;">{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayEnd,'USD')}</font>
		<b>{$oLanguage->PrintCurrencyPrice($aSubcontal.dTodayEndCurrency,$sCodeCurrency)}</b>
	</td>
</tr>