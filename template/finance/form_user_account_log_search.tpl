<table{*  width=100% *}>

<tr>
	<td {* width=50% *} >{$oLanguage->GetMessage("Account money")}</td>
	<td ><nobr>
		<strong style="color:{if $aAuthUser.amount>0}green{else}red{/if}">{$oCurrency->PrintPrice($aAuthUser.amount)}</strong>

	</nobr>
	</td>
	<td >
			<label>&nbsp;
	            <input name=search[date] value="1" type="checkbox" class="js-custom-checkbox"
	            {if $smarty.request.search.date}checked{/if}>
	        </label>&nbsp;
			{$oLanguage->getMessage("DFrom")}: 
		</td>
		<td>
			<input id=date_from name=date_from type="text" {*  style='width:100px;' *}
				readonly value='{if $smarty.request.date_from}{$smarty.request.date_from}
					{else}{$smarty.now-30*86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, this, 'dd.mm.yyyy')">
   		</td>
	</tr>
{if $aAuthUser.type_=='customer'}
	{if $aAuthUser.price_type=='discount'}
<tr>
	<td >{$oLanguage->getMessage("Discount")}: {$oLanguage->getContextHint("customer_finance_discount")}</td>
	<td >{$sDiscount} % </td>
	<td>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$oLanguage->getMessage("DTo")}:
		</td>
		<td>
			<input id=date_to name=date_to type="text" {* style='width:100px;' *}
				readonly value='{if $smarty.request.date_to}{$smarty.request.date_to}
					{else}{$smarty.now+86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, this, 'dd.mm.yyyy')">
		</td>
</tr>
	{/if}
{/if}

</table>