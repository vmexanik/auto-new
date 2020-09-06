<form id="filter_form" name="filter_form" action="javascript:void(null)" onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>Filter</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1 width=850>
			<tr>
				<td>{$oLanguage->getDMessage('Date from')}:</td>
				<td><input id=date_from name=search[date_from] style='width: 80px;'
					readonly="readonly" value="{if $aSearch.date_from}
													{$aSearch.date_from|escape}
												{else}
													{$sSearchDate01Month|date_format:'%d.%m.%Y'}
												{/if}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
				</td>
				<td>{$oLanguage->getDMessage('Date To')}:</td>
				<td><input id=date_to name=search[date_to] style='width: 80px;'
					readonly="readonly" value="{if $aSearch.date_to}
													{$aSearch.date_to|escape}
												{else}
													{$smarty.now+86400|date_format:'%d.%m.%Y'}
												{/if}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
				</td>
				<td>{$oLanguage->getDMessage('Report type')}:</td>
				<td>
				 	{html_options name=search[report_type]  options=$aReportType
				 		selected=$aSearch.report_type style='width: 130px'}
				</td>

				<td>{$oLanguage->getDMessage('Subconto1')}:</td>
				<td>
				 	{html_options name=search[id_account]  options=$aAccount
				 		selected=$aSearch.id_account style="width: 200px"}
				</td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<input type=button class='bttn' value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
<input type=submit value='Search' class='bttn'>

<input type=hidden name=action value={$sBaseAction}_search>
<input type=hidden name=return value="{$sSearchReturn|escape}">

<a href="?action={$sBaseAction}_exportperiod"
	onclick="xajax_process_browse_url(this.href+'&date_from='+$('#date_from').val()
		+'&date_to='+$('#date_to').val()); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/outbox.png"/
	>{$oLanguage->GetDMessage('Export all in selected period')}</a>
	
</form>
<div id="export_file_all"></div>
{if $aSearch.id_account}
<a href="?action={$sBaseAction}_exportselected"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/outbox.png"/
	>{$oLanguage->GetDMessage('Export results to excel')}</a>
<div id="export_file_selected_id">
	
</div>
{/if}
