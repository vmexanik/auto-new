<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Form Item')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=100%>{$oLanguage->getDMessage('Caption')}:{$sZir}</td>
   <td><input type=text name=data[caption] value='{$aData.caption}' ></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Type')}:{$sZir}</td>
   <td>
   	<select name=data[type]>
 		{html_options options=$typeInput selected=$aData.type}
	</select>
   </td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Oredr Num')}:{$sZir}</td>
   <td><input type=text name=data[num] value='{$aData.num}'></td>
  </tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id_form] value={$aData.id_form}>
<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>