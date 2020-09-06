<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Account')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td>{$oLanguage->getDMessage('IdBuh')}:{$sZir}</td>
   <td><input type=text name=data[id_buh] value="{$aData.id_buh|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Currency')}:</td>
    <td>
   {html_options name=data[id_currency] options=$aCurrencyAssoc selected=$aData.id_currency}
  </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('title')}:{$sZir}</td>
   <td><input type=text name=data[title] value="{$aData.title|escape}"></td>
</tr>


{*<tr>
   <td width=100%>{$oLanguage->getDMessage('Office')}:</td>
   <td>
		{html_options name="data[id_office]" options=$aOffice selected=$aData.id_office}
   </td>
</tr>*}

<tr>
   <td>{$oLanguage->getDMessage('account_id')}:{$sZir}</td>
   <td><input type=text name=data[account_id] value="{$aData.account_id|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('holder_name')}:{$sZir}</td>
   <td><input type=text name=data[holder_name] value="{$aData.holder_name|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('holder_code')}:{$sZir}</td>
   <td><input type=text name=data[holder_code] value="{$aData.holder_code|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('holder_kpp')}:{$sZir}</td>
   <td><input type=text name=data[holder_kpp] value="{$aData.holder_kpp|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('bank_name')}:{$sZir}</td>
   <td><input type=text name=data[bank_name] value="{$aData.bank_name|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('bank_code')}:{$sZir}</td>
   <td><input type=text name=data[bank_code] value="{$aData.bank_code|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('correspondent_account')}:</td>
   <td><input type=text name=data[correspondent_account] value="{$aData.correspondent_account|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('bank_mfo')}:{$sZir}</td>
   <td><input type=text name=data[bank_mfo] value="{$aData.bank_mfo|escape}"></td>
</tr>

<tr>
   <td>{$oLanguage->getDMessage('holder_sign')}:{$sZir}</td>
   <td><input type=text name=data[holder_sign] value="{$aData.holder_sign|escape}"></td>
</tr>


<tr>
   <td width=50%>{$oLanguage->getDMessage('Description')}:</td>
   <td><textarea name=data[description]>{$aData.description|escape}</textarea></td>
</tr>
{include file='addon/mpanel/form_visible.tpl' aData=$aData}

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>