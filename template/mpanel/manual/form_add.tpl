<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('CManual')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('user type')}:{$sZir}</td>
   <td>{html_options name=data[code_manual_category] options=$aManualCategoryHash
   selected=$aData.code_manual_category onchange='man.ChangeManualCode()' id='data_code_manual_category'}</td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value="{$aData.code|escape}" id="data_code"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Short Content')}:</td>
	<td><input type=text name=data[short_content] value="{$aData.short_content|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Content')}:</td>
	<td>{$oAdmin->getCKEditor('data[content]',$aData.content)}</td>
</tr>
{include file='addon/mpanel/form_visible.tpl' aData=$aData}
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
<input type=hidden name=data[num] value="{$aData.num|escape}" id="data_number">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>