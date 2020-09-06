<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Manager')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Login')}:{$sZir}</td>
   <td><input type=text name=data[login] value="{$aData.login|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Password')}:{$sZir}</td>
   <td><input type=text name=data[password] value="{$aData.password|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>

{include file='addon/mpanel/form_image.tpl' aData=$aData}

<tr>
   <td width=50%>{$oLanguage->getDMessage('Country')}:</td>
   <td><input type=text name=data[country] value="{$aData.country|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('State')}:</td>
   <td><input type=text name=data[state] value="{$aData.state|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('City')}:</td>
   <td><input type=text name=data[city] value="{$aData.city|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Zip')}:</td>
   <td><input type=text name=data[zip] value="{$aData.zip|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Company')}:</td>
   <td><input type=text name=data[company] value="{$aData.company|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Address')}:</td>
   <td><input type=text name=data[address] value="{$aData.address|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Email')}:</td>
   <td><input type=text name=data[email] value="{$aData.email|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Phone')}:</td>
   <td><input type=text name=data[phone] value="{$aData.phone|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Phone 2')}:</td>
   <td><input type=text name=data[phone2] value="{$aData.phone2|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Mobile Phone')}:</td>
   <td><input type=text name=data[phone3] value="{$aData.phone3|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Skype')}:</td>
   <td><input type=text name=data[skype] value="{$aData.skype|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Icq')}:</td>
   <td><input type=text name=data[icq] value="{$aData.icq|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Remarks')}:</td>
   <td><textarea name=data[remark]>{$aData.remark}</textarea></td>
</tr>

{include file='addon/mpanel/form_visible.tpl' aData=$aData}

<tr>
   <td>{$oLanguage->getDMessage('Approved')}:</td>
   <td><input type="hidden" name=data[approved] value="0">
   <input type=checkbox name=data[approved] value='1' style="width:22px;" {if $aData.approved}checked{/if}></td>
</tr><tr>
   <td>{$oLanguage->getDMessage('Has Customers')}:</td>
   <td><input type="hidden" name=data[has_customer] value="0">
   <input type=checkbox name=data[has_customer] value='1' style="width:22px;" {if $aData.has_customer}checked{/if}></td>
</tr><tr>
   <td>{$oLanguage->getDMessage('Is Super Manager')}:</td>
   <td><input type="hidden" name=data[is_super_manager] value="0">
   <input type=checkbox name=data[is_super_manager] value='1' style="width:22px;" {if $aData.is_super_manager}checked{/if}></td>
</tr><tr>
   <td>{$oLanguage->getDMessage('Is Sub Manager')}:</td>
   <td><input type="hidden" name=data[is_sub_manager] value="0">
   <input type=checkbox name=data[is_sub_manager] value='1' style="width:22px;" {if $aData.is_sub_manager}checked{/if}></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
<input type=hidden name=data[type_] value="manager">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>