<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table width=800 border=0 cellspacing=0 cellpadding=0>
<tr>
   <td valign=top>


<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 Роли
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Description')}:</td>
   <td><input type=text name=data[description] value="{$aData.description|escape}"></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel2/base_add_button.tpl' sBaseAction=$sBaseAction}



</td>

</tr>
</table>

</FORM>