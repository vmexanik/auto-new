{if $aSearchResult.id}
<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Customer')}</th>
	</tr>
	<tr>
		<td>
		<table cellspacing=2 cellpadding=1 width=850>
		<tr>
			<td><b>{$oLanguage->getDMessage('Group')}</b>
				{if $aSearchResult.type_=='customer'}
					<font color=red><b>{$aSearchResult.price_type}</b></font>
				{else}
					<font color=red><b>{$aSearchResult.pg_name}</b> {$aSearchResult.name_currency}</font>
				{/if}
			</td>
			<td>{$aSearchResult.customer_group_name} </td>
			<td><b>{$oLanguage->getDMessage('Email')}</b></td>
			<td>{$aSearchResult.email}</td>
			<td><b>{$oLanguage->getDMessage('Phone')}</b></td>
			<td>{$aSearchResult.phone}</td>
			<td><b>{$oLanguage->getDMessage('GDebt')}</b></td>
			<td>{$aSearchResult.group_debt}</td>
		</tr>
		<tr>
			<td><b>{$oLanguage->getDMessage('GDebtPercent')}</b></td>
			<td>{$aSearchResult.group_debt_percent}%</td>
			<td><b>{$oLanguage->getDMessage('на счету')}</b></td>
			<td>{*$aSearchResult.amount*}{$dBalance}
			<a href="?action=customer_deposit&id={$aSearchResult.id}&user_id={$aSearchResult.id_user}&id_user_referer={$aSearchResult.id_user_referer}&call_action=customer&return={$sReturn|escape:"url"}" onclick="xajax_process_browse_url(this.href); return false;">
			<img border=0 src="/libp/mpanel/images/small/inbox.png"  hspace=3 align=absmiddle/>{$oLanguage->getDMessage('Deposit')}</a>

			</td>
			<td><b>{$oLanguage->getDMessage('Долг по заказам')}</b></td>
			<td>{$aSearchResult.amount_debt}</td>
			<td><b>{$oLanguage->getDMessage('Баланс')}</b></td>
			<td>{*$aSearchResult.amount-$aSearchResult.amount_debt*}{$dBalance}</td>
		</tr>
		<tr>
			<td><b>{$oLanguage->getDMessage('На складе на сумму')}</b></td>
			<td>{$aSearchResult.amount_store}</td>
			<td><b>{$oLanguage->getDMessage('Долг по выданному')}</b></td>
			<td>{$aSearchResult.amount_debt_end}</td>
			<td>&nbsp;</td><td>&nbsp;</td><td>
			<a href="?action=customer_set_balance&id={$aSearchResult.id}&user_id={$aSearchResult.id_user}&id_user_referer={$aSearchResult.id_user_referer}&call_action=customer&return={$sReturn|escape:"url"}" onclick="xajax_process_browse_url(this.href); return false;">
			<img border=0 src="/libp/mpanel/images/small/inbox.png"  hspace=3 align=absmiddle/>{$oLanguage->getDMessage('Set balance')}</a>
			</td>
		</tr>
		{if $aSearchResult.type_=='customer'}
			{if $aSearchResult.price_type=='discount'}
			<tr>
				<td><b>{$oLanguage->getDMessage('GDiscount')}</b></td>
				<td>{$aSearchResult.group_discount}%</td>
				<td><b>{$oLanguage->getDMessage('DiscountStatic')}</b></td>
				<td>{$aSearchResult.discount_static}%</td>
				<td><b>{$oLanguage->getDMessage('DiscountDinamic')}</b></td>
				<td colspan=3>{$aSearchResult.discount_dynamic}%</td>
			</tr>
			{else}
			<tr>
				<td><b>{$oLanguage->getDMessage('Margin')}</b></td>
				<td colspan=7>{math equation="x + y" x=$aSearchResult.customer_group_margin y=$aSearchResult.parent_margin}  %</td>
			</tr>
			{/if}
		{/if}
		</table>
		</td>
	</tr>
</table>
{/if}