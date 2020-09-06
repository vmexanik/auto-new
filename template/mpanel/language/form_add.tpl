<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Language')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Domain')}:</td>
   <td><input type=text name=data[domain] value="{$aData.domain|escape}"></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value="{$aData.code|escape}"></td>
  </tr>

{include file='addon/mpanel/form_image.tpl' aData=$aData}

{include file='addon/mpanel/form_visible.tpl' aData=$aData}

  <tr>
   <td>{$oLanguage->getDMessage('Num')}:</td>
   <td><input type=text name=data[num] value="{$aData.num|escape}"></td>
  </tr>
  </table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>