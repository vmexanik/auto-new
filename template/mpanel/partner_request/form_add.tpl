<form id="main_form" action="javascript:void(null);" onsubmit="submit_form(this);">

<table cellspacing="0" cellpadding="2" class="add_form">
<tr>
	<th>{$oLanguage->getDMessage('Partner Request')}</th>
</tr>
<tr><td>

	<table cellspacing="2" cellpadding="1">

	<tr>
		<td width="50%">{$oLanguage->getDMessage('email')}:</td>
		<td><input type="text" name="data[email]" value="{$aData.email|escape}" ></td>
	</tr>


	<tr>
		<td width="50%">{$oLanguage->getDMessage('Login')}:</td>
		<td><input type="text" name="data[login]" value="{$aData.login|escape}" ></td>
	</tr>

	<tr>
	   <td width="50%">{$oLanguage->getDMessage('Region')}:</td>
	   <td>{html_options name="data[id_partner_region]" options=$aPartnerRegion selected=$iPartnerSelected disabled=true}</td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Address')}:</td>
		<td><input type="text" name="data[address]" value="{$aData.address|escape}" ></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Phone')}:</td>
		<td><input type="text" name="data[phone]" value="{$aData.phone|escape}" ></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Sales Volume')}:</td>
		<td><input type="text" name="data[sales_volume]" value="{$aData.sales_volume|escape}" ></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Company')}:</td>
		<td><input type="text" name="data[company]" value="{$aData.company|escape}" ></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Own Site')}:</td>
		<td><input type="text" name="data[own_site]" value="{$aData.own_site|escape}" ></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Manager Number')}:</td>
		<td><input type="text" name="data[manager_number]" value="{$aData.manager_number|escape}" ></td>
	</tr>
<tr>
   <td>{$oLanguage->GetDMessage('Storage')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='storage' bChecked=$aData.storage}</td>
</tr>
<tr>
   <td>{$oLanguage->GetDMessage('Own parts')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='own_part' bChecked=$aData.own_part}</td>
</tr>

<tr>
   <td>{$oLanguage->GetDMessage('Is Called')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_called' bChecked=$aData.is_called}</td>
</tr>
<tr>
	<td width="100%">{$oLanguage->GetDMessage('Call Comment')}:</td>
	<td><textarea name=data[call_comment]>{$aData.call_comment}</textarea></td>
</tr>

	</table>

</td></tr>
</table>

<input type="hidden" name="data[id]" value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</form>