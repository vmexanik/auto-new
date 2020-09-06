<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Currencies')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
{foreach from=$aCurrency item=currency}
<td><b>{$currency.code}({$currency.value})</b>
		<input type='text' name='currency_{$currency.code}' value='' maxlength=8 style="width:50px"
			onKeyUp="document.getElementById('amount').value= Math.round(this.value/{$currency.value} *100)/100; "
		>
	</td>
{/foreach}
</tr>
</table>

</td></tr>
</table>

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Deposit for')}:<font color=green><b>{$aData.login|escape}</b></font>
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Amount')}:</td>
   <td><input type=text id=amount name=data[amount] value="0"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Custom ID')}:</td>
   <td><input type=text name=data[custom_id] value="0"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Pay Type')}:</td>
    <td>
   {html_options name=data[pay_type] options=$aPayTypeList selected=$sPayTypeSelected}
  </td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Section')}:</td>
    <td>
   {html_options name=data[section] options=$aSectionList selected=$sSectionSelected}
  </td>
</tr>

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=provider_deposit}

</FORM>