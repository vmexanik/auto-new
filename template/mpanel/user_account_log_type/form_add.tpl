<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('User Account Log Type')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->GetDMessage('ID')}:{$sZir}</td>
   <td><input type=text name=data[id] value="{$aData.id|escape}"
		{if $aData.id}readonly{/if} />
	{if !$aData.id}
		<input type=hidden name=data[add] value='1'>
	{/if}
   </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}" /></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Description')}:</td>
   <td><textarea name=data[description]>{$aData.description}</textarea></td>
</tr>

</table>

</td></tr>
</table>

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>