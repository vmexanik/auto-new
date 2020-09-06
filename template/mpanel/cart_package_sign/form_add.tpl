<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('CartPackageSign')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value="{$aData.code|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}{$sZir}:</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('Description')}:{$sZir}</td>
   <td><textarea name=data[description]>{$aData.description}</textarea></td>
</tr>
{include file='addon/mpanel/form_visible.tpl' aData=$aData}
<tr>
   <td width=50%>{$oLanguage->getDMessage('Num')}:</td>
   <td><input type=text name=data[num] value="{$aData.num|escape}"></td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('DefaultCheck')}:</td>
   <td><input type="hidden" name=data[visible] value="0">
   <input type=checkbox name=data[default_check] value='1' style="width:22px;" {if $aData.default_check}checked{/if}></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>