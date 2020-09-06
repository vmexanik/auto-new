<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('User Account Log')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td>{$oLanguage->getDMessage('ID Account')}: {$sZir}</td>
   <td>
 		{html_options name=data[id_account] options=$aAccount selected=$aData.id_account}
   </td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Pay Type')}:</td>
   <td>
 		{html_options name=data[pay_type] values=$aPayTypeId output=$aPayTypeValue selected=$aData.pay_type}
   </td>
  </tr>
   <tr>
   <td>{$oLanguage->getDMessage('Section')}:</td>
   <td>
   		{html_options name=data[section] values=$aSectionId output=$aSectionValue selected=$aData.section}
   </td>
  </tr>
  <tr>
  	<td>{$oLanguage->getDMessage('Custom ID')}:</td>
  	<td><input type=text name=data[custom_id] value="{$aData.custom_id|escape}"></td>
  </tr>
  <tr id="description_block">
   <td>{$oLanguage->getDMessage('Description')}:</td>
   <td><textarea name=data[description] rows=4>{$aData.description|escape}</textarea></td></td>
  </tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>