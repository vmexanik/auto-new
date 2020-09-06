
<form id="filter_form" name="filter_form" action="javascript:void(null)" onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Filter')}:</th>
	</tr>
	<tr>
		<td>
	{include file="mpanel/user_account_log/search_additional_yestarday.tpl"}

		<table cellspacing=2 cellpadding=1 width=850 >
			<tr>
				<td valign=top>{$oLanguage->getDMessage('ULogin')}:</td>
				<td><input type=text name=search[login]
					value="{$aSearch.login|escape}" maxlength=30
					style='width: 80px'>
					<select name=search[user_type] style='width: 75px'>
						{html_options options=$aUserType selected=$aSearch.user_type}
					</select>
<br>

	<select name=search[id_partner_region] style='width: 160px'>
   		<option value=0>{$oLanguage->getDMessage("Other region")}</option>
		{foreach from=$aPartnerRegion item=aItem}
		<option value={$aItem.id}
			{if $aItem.id == $aSearch.id_partner_region} selected {/if}
				> {$aItem.name}</option>
		{/foreach}
	</select>
				</td>

				<td>{$oLanguage->getDMessage('Date from')}:</td>
				<td><input id=date_from name=search[date_from] style='width: 80px;'
					readonly="readonly" value="{strip}{if $aSearch.date_from}
													{$aSearch.date_from|escape}
												{else}
													{$sFromDate|date_format:'%d.%m.%Y'}
												{/if}{/strip}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');"></td>

				<td>{$oLanguage->getDMessage('Date To')}:</td>
				<td><input id=date_to name=search[date_to] style='width: 80px;'
					readonly="readonly" value="{strip}{if $aSearch.date_to}
													{$aSearch.date_to|escape}
												{else}
													{$sForDate|date_format:'%d.%m.%Y'}
												{/if}{/strip}"
					onclick="popUpCalendar(this, this, 'dd.mm.yyyy');"></td>
				<td>{$oLanguage->getDMessage('Provider Invoice')}:</td>
				<td colspan="2"><input type=text name=search[id_provider_invoice]
					value="{$aSearch.id_provider_invoice|escape}" maxlength=150
					style='width: 110px'></td>
				<td></td>
			</tr>
			<tr>
				<td>{$oLanguage->getDMessage('Amount')}:</td>
				<td><input type=text name=search[amount]
					value="{$aSearch.amount|escape}" maxlength=20
					style='width: 110px'></td>

				<td>{$oLanguage->getDMessage('Description')}:</td>
				<td><input type=text name=search[description]
					value="{$aSearch.description|escape}" maxlength=150
					style='width: 110px'>
				</td>

				<td>{$oLanguage->getDMessage('Data')}:</td>
				<td><input type=text name=search[data]
					value="{$aSearch.data|escape}" maxlength=150
					style='width: 110px'>
				</td>

				<td>{$oLanguage->getDMessage('Account')}:</td>
				<td><nobr>
				 	{html_options name=search[id_account][]  options=$aAccount
				 		selected=$aSelectedIdAccount style="width:$iSearchIdAccountWidth"
				 		multiple='multiple' size=$iSearchIdAccountSize}
				</td>
				<td>
					<a href="{strip}?action=account_move_money&return={$sReturn|escape:"url"}{/strip}"
		onclick="xajax_process_browse_url(this.href); return false;"
		title="{$oLanguage->getDMessage('Move between accounts')}">
			<img border=0 src="/libp/mpanel/images/small/inout.png"  hspace=3 align=absmiddle/>
					</a>{*<br/><br/>
					<a href="{strip}?action=customer_deposit&call_action=customer&return={$sReturn|escape:"url"}{/strip}"
		onclick="xajax_process_browse_url(this.href); return false;"
		title="{$oLanguage->getDMessage('deposit')}">
			<img border=0 src="/libp/mpanel/images/small/inbox.png"  hspace=3 align=absmiddle/>
					</a>*}
				</td>
			</tr>
			<tr>
				<td>{$oLanguage->getDMessage('Type')}:</td>
				<td><select name=search[type_] style='width:110px'>
					 {html_options options=$aType selected=$aSearch.type_}
					</select></td>

				<td>{$oLanguage->getDMessage('Section')}:</td>
				<td><select name=search[section] style='width:110px'>
						<option value=''>{$oLanguage->GetDMessage('All')}</option>
   						{html_options values=$aSection selected=$aSearch.section output=$aSection}
					</select></td>

				<td>{$oLanguage->getDMessage('Custom Id')}:</td>
				<td><input type=text name=search[custom_id]
					value="{$aSearch.custom_id|escape}" maxlength=20
					style='width: 110px'></td>

				<td>{$oLanguage->getDMessage('Account Log Type')}:</td>
				<td>
				<select name=search[id_user_account_log_type]  style="width:110px">
					<option value=''>{$oLanguage->GetDMessage('All')}</option>
				 	{html_options  options=$aUserAccountLogType	selected=$aSearch.id_user_account_log_type}
				</select>
				</td>
				<td></td>
			</tr>
		</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="140" nowrap><input type=button class='bttn' value="{$oLanguage->getDMessage('Clear')}"
	onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
<input type=submit value='Search' class='bttn'>
</td>
    <td nowrap>
{if $dTotalAmountDebet || $dTotalAmountCredit}
  <b><span id="total_amount">
    {$oLanguage->GetDMessage('Debet:')} <span style="color:{if $dTotalAmountDebet>0}green{else}red{/if}">{$dTotalAmountDebet}</span>
    {$oLanguage->GetDMessage('Credit:')} <span style="color:{if $dTotalAmountCredit>0}green{else}red{/if}">{$dTotalAmountCredit}</span>
    {$oLanguage->GetDMessage('на счету')}: <span style="color:{if $dTotalAmount>0}green{else}red{/if}">{$dTotalAmount}</span>
    {*
	{$oLanguage->GetDMessage('Долг по заказам')}: <span style="color:{if $dAmountDebt>0}green{else}red{/if}">{$dAmountDebt}</span>
    {$oLanguage->GetDMessage('Баланс')}: <span style="color:{if $dTotalAmount-$dAmountDebt>0}green{else}red{/if}">{$dTotalAmount-$dAmountDebt}</span>
    <br>
	{$oLanguage->GetDMessage('На складе на сумму')}: <span style="color:{if $dAmountStore>0}green{else}red{/if}">{$dAmountStore}</span>
    {$oLanguage->GetDMessage('Долг по выданному')}: <span style="color:{if $dAmountDebtEnd>0}green{else}red{/if}">{$dAmountDebtEnd}</span>
	*}
    </span></b>

    &nbsp;&nbsp;&nbsp;

    <a href="?action={$sBaseAction}_export"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/outbox.png"/
	>{$oLanguage->GetDMessage('Export to excel')}</a>
{/if}
    </td>
  </tr>
</table>

<div id="export_file_id"></div>

<input type=hidden name=action value={$sBaseAction}_search>
<input type=hidden name=return value="{$sSearchReturn|escape}">

</form>