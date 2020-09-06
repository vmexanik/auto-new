<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('DirectoryConfig')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
  <tr>
   <td width=100%>{$oLanguage->getDMessage('Display Select')}:</td>
   <td>{html_options name=data[display_select] options=$aDisplaySelect selected=$aData.display_select}</td>
  </tr>
  <tr>
   <td width=50%>{$oLanguage->getDMessage('OrderField')}:</td>
   <td><input type=text name=data[order_field] value="{$aData.order_field|escape}"></td>
  </tr>
  <tr>
   <td>{$oLanguage->getDMessage('Order Way')}:</td>
   <td>{html_options name=data[order_way] options=$aOrderWay selected=$aData.order_way}</td>
  </tr>
  </table>

</td></tr>
</table>

<input type=hidden name=action value=directory_config_apply>
<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>