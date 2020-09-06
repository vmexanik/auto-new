<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Attachment')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('owner_code')}:{$sZir}</td>
   <td><input type=text name=data[owner_code] value='{$aData.owner_code}'></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[attach_file] value='{$aData.attach_file}'></td>
</tr>

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
<input type=hidden name=data[owner_code] value="{$aData.owner_code|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>
