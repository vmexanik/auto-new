<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Bill')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('User')}:</td>
   <td>
   {html_options name=data[id_user] options=$aUserList selected=$sUserSelected}
  </td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Template')}:</td>
    <td>
   {html_options name=data[code_template] options=$aTemplateList selected=$sTemplateSelected}
  </td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Amount')}:{$sZir}</td>
   <td><input type=text name=data[amount] value="{$aData.amount|escape}"></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>