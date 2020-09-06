<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('FAQ Category')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=50%>{$oLanguage->getDMessage('Position')}:</td>
   <td nowrap>
   	<select name=scope style="width:80">
 	{html_options options=$aScope selected=$aReq.scope}
	</select>
   	<select name=idtree style="width:218">
 	{html_options options=$aTree selected=$aReq.idtree}
	</select>
   </td>
  </tr>
   <tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value='{$aData.name}'></td>
  </tr>
  <tr>
   <td width=50%>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value='{$aData.code}'></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Visible')}:</td>
   <td><input type=hidden name=data[visible] value='0'>
   <input type=checkbox name=data[visible] value='1' style="width:22px;" {if $aData.visible}checked{/if}></td>
  </tr>
  </table>

</td></tr>
</table>

<input type=hidden name=data[id] value='{$aData.id}'>

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>