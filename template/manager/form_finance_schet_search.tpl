<table width=700 border=0>
	<tr>
		<td><b>{$oLanguage->getMessage("Office")}:</b></td>
		<td>{html_options name="search[id_office]" options=$aOffice selected=$smarty.request.search.id_office}</td>

		<td><b>{$oLanguage->getMessage("Schet")}:</b></td>
		<td>{html_options name="search[id_account]" options=$aAccount selected=$smarty.request.search.id_account}</td>
		</td>
	</tr>
	<tr>
		<td>
		<input type=checkbox name=search[date] value=1 {if $smarty.request.search.date}checked{/if}>
		<b>{$oLanguage->getMessage("DFrom")}:</b></td>
		<td><input id=date_from name=search[date_from]  style='width:100px;'
	readonly value='{if $smarty.request.date_from}{$smarty.request.search.date_from}
					{else}{$smarty.now-30*86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_from'), 'dd.mm.yyyy')">
		</td>
		<td><b>{$oLanguage->getMessage("DTo")}:</b></td>
		<td><input id=date_to name=search[date_to]  style='width:100px;'
			readonly value='{if $smarty.request.search.date_to}{$smarty.request.search.date_to}
					{else}{$smarty.now+86400|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_to'), 'dd.mm.yyyy')">
		</td>

	</tr>
</table>