<table width=100% border=0>
	<tr>
		<td>
		<b>{$oLanguage->getMessage("DFrom")}:</b></td>
		<td><input id=date_from name=search[date_from]  type="text" style='width:100px;'
				readonly value='{if $smarty.request.search.date_from}{$smarty.request.search.date_from}{else
					}{$smarty.now|date_format:"01.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_from'), 'dd.mm.yyyy')">
		</td>
		<td><b>{$oLanguage->getMessage("DTo")}:</b></td>
		<td><input id=date_to name=search[date_to] type="text" style='width:100px;'
				readonly value='{if $smarty.request.search.date_to}{$smarty.request.search.date_to}{else
					}{$smarty.now|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_to'), 'dd.mm.yyyy')">
		</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Type report")}:</b></td>
		<td>
		{html_options name=search_type_report options=$aTypeReport selected=$smarty.request.search_type_report class="js-select" style="width:203px;"}
		</td>
	</tr>
</table>
<input type='hidden' name='is_post' value='1'>
