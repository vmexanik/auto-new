<table width=100% border=0>
	<tr>
		<td><b>{$oLanguage->getMessage("Customer")}:</b></td>
		<td><input type=text name=search[login] value='{$smarty.request.search.login}'>

		<td><b>{$oLanguage->getMessage("Template")}:</b></td>
		<td>
		{html_options name=search[code_template] options=$aCodeTemplate selected=$smarty.request.search.code_template}
		</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("id")}:</b></td>
		<td><input type=text name=search[id] value='{$smarty.request.search.id}' >
		<td><b>{$oLanguage->getMessage("amount")}:</b></td>
		<td><input type=text name=search[amount] value='{$smarty.request.search.amount}' >
	</tr>
	<tr>
		<td>
		<input type=checkbox name=search_date value=1 checked>
		<b>{$oLanguage->getMessage("DFrom")}:</b></td>
		<td><input id=date_from name=search[date_from]  style='width:100px;'
				readonly value='{if $smarty.request.search.date_from}{$smarty.request.search.date_from}{else
					}{$smarty.now-60*86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_from'), 'dd.mm.yyyy')">
		</td>
		<td><b>{$oLanguage->getMessage("DTo")}:</b></td>
		<td><input id=date_to name=search[date_to]  style='width:100px;'
				readonly value='{if $smarty.request.search.date_to}{$smarty.request.search.date_to}{else
					}{$smarty.now+86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_to'), 'dd.mm.yyyy')">
		</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("order")}:</b></td>
		<td><input type=text name=search[id_cart_package] value='{$smarty.request.search.id_cart_package}' >
	</tr>
</table>