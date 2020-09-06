<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Glossary')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=100%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value='{$aData.name}' ></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('First letter')}:{$sZir}</td>
   <td><input type=text name=data[first_letter] value='{$aData.first_letter}'></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Title')}:{$sZir}</td>
   <td><input type=text name=data[title] value='{$aData.title}'></td>
  </tr>
  <tr>
	   <td width=50%>{$oLanguage->getDMessage('Description')}:{$sZir}</td>
	   <td>{$oAdmin->getCKEditor('data[description]',$aData.description, 650, 300)}</td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Status')}:{$sZir}</td>
   <td><input type="hidden" name=data[status] value="0">
   	<input type=checkbox name=data[status] value='1' style="width:22px;" {if $aData.status}checked{/if}></td>
  </tr>
</table>

</td></tr>
</table>

<input type=hidden name=action value=admin_apply>
<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>