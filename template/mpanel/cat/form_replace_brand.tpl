<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)" >
<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th style="white-space:nowrap;">
 {$oLanguage->getDMessage('Brand')} [{$aCat.title}] {$oLanguage->getDMessage('pref')} [{$aCat.pref}] id_tof [{$aCat.id_tof}] {$oLanguage->getDMessage('price_item')} [{$iCntPrice}]
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('brand for replace')}:</td>
   <td>{html_options name=data[id_cat_replace] options=$aCatReplace}
   </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('link to selected brand')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_link_selected' bChecked=$aData.is_link_selected}</td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aCat.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction='cat_replace'}

</FORM>