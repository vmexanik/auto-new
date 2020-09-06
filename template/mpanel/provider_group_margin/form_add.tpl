<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Provider Group margin')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Provider Group')}:{$sZir}</td>
    <td>
   {html_options name=data[id_provider_group] options=$aProviderGroup selected=$aData.id_provider_group}
  </td>
</tr>

<!--tr>
   <td width=50%>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value="{$aData.code|escape}"></td>
</tr-->

<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Price From')}:{$sZir}</td>
   <td><input type=text name=data[price_from] value="{$aData.price_from|escape}"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Price To')}:{$sZir}</td>
   <td><input type=text name=data[price_to] value="{$aData.price_to|escape}"></td>
</tr>

<tr>
   <td width=50%>{$oLanguage->getDMessage('Group Margin')}:{$sZir}</td>
   <td><input type=text name=data[margin] value="{$aData.margin|escape}"></td>
</tr>


{include file='addon/mpanel/form_visible.tpl' aData=$aData}

</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>