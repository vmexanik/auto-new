<table width=100% border=0>
	<tr>
		<td>
		<b>{$oLanguage->getMessage("DFrom")}:</b></td>
		<td><input id=date_from type="text" name=search[date_from]  style='width:100px;'
				readonly value='{if $smarty.request.search.date_from}{$smarty.request.search.date_from}{else
					}{$smarty.now|date_format:"01.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_from'), 'dd.mm.yyyy')">
		</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("DTo")}:</b></td>
		<td><input id=date_to type="text" name=search[date_to]  style='width:100px;'
				readonly value='{if $smarty.request.search.date_to}{$smarty.request.search.date_to}{else
					}{$smarty.now|date_format:"%d.%m.%Y"}{/if}'
   			 	onclick="popUpCalendar(this, document.getElementById('date_to'), 'dd.mm.yyyy')">
		</td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Customer")}:</b></td>
		<td><div id="sel_customer">{include file="finance/select_customer.tpl"}</div></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Manager")}:</b></td>
		<td>{html_options class="select_name_user" id=select_search_manager name=select_search_manager options=$aNameManager onchange="return change_manager('select_search_manager')"
				selected=$smarty.request.select_search_manager style="width:203px;"}
		</td>
	</tr>

	<tr>
		<td><b>{$oLanguage->getMessage("Type report")}:</b></td>
		<td>
		{html_options name=search_type_report class="select_name_user" options=$aTypeReport selected=$smarty.request.search_type_report style="width:203px;"}
		</td>
	</tr>
	<tr><td>&nbsp;</td><td>
		<input type=checkbox class="js-checkbox" name=search_date value=1 {if $smarty.request.search_date}checked{/if}>{$oLanguage->getMessage("by dates")}
		</td>
	</tr>
</table>
<input type='hidden' name='is_post' value='1'>
