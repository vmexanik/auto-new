<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->GetDMessage('Directory Sto')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('ID User')}:</td>
   <td><input type=text name=data[id_user] value="{$aData.id_user|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->GetDMessage('City')}:</td>
   <td>
   {html_options name=data[id_directory_city] options=$aCity selected=$aData.id_directory_city}
  </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('City Region')}:</td>
   <td><input type=text name=data[city_region] value="{$aData.city_region|escape}"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->GetDMessage('Description')}:</td>
   <td><textarea name=data[description]>{$aData.description}</textarea></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('Address')}:</td>
   <td><input type=text name=data[address] value="{$aData.address|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('Phone')}:</td>
   <td><input type=text name=data[phone] value="{$aData.phone|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('Url')}:</td>
   <td><input type=text name=data[url] value="{$aData.url|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('STO Discount')}:</td>
   <td><input type=text name=data[discount] value="{$aData.discount|escape}"></td>
</tr>

{include file='addon/mpanel/form_image.tpl' aData=$aData}

{include file='addon/mpanel/form_visible.tpl' aData=$aData}

<tr>
   <td>{$oLanguage->GetDMessage('Is Featured')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_featured' bChecked=$aData.is_featured}</td>
</tr>
<tr>
   <td>{$oLanguage->GetDMessage('Services')}:</td>
   <td>
<select name=data[directory_sto_tag][] multiple="multiple" size="10">
{foreach from=$aDirectoryTag item=aItem}
	<option value="{$aItem.id}" {if in_array($aItem.id,$aDirectoryStoTag)}selected{/if}>{$aItem.name}</option>
{/foreach}
</select>
   </td>
</tr>

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>