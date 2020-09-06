<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Price group pref')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('id_price_group')}:{$sZir}</td>
   <td><input type=text name=data[id_price_group] value="{$aData.id_price_group|escape}" /></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('pref')}:{$sZir}</td>
   <td><input type=text name=data[pref] value="{$aData.pref|escape}" maxlength="3" /></td>
</tr>

{include file='addon/mpanel/form_visible.tpl' aData=$aData}

</table>
</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>
