<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Contact Form')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=100%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value='{$aData.name}' ></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value='{$aData.code}'></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Caption')}:{$sZir}</td>
   <td><input type=text name=data[caption] value='{$aData.caption}'></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('To Email')}:</td>
   <td><input type=text name=data[to_email] value='{$aData.to_email}'></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Set Active')}:{$sZir}</td>
   <td><input type="hidden" name=data[active] value="0">
   <input type=checkbox name=data[active] value='1' style="width:22px;" {if $aData.active}checked{/if}></td>
  </tr>
</table>

</td></tr>
</table>

<input type=hidden name=action value=admin_apply>
<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>