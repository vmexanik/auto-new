<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Item')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>

{include file='mpanel/page_manager/form_add_part.tpl'}

</table>

</td></tr>
</table>

<input type=hidden name=data[level] value="{$aData.level|escape}">
<input type=hidden name=data[id] value="{$aData.id|escape}">
<input type=hidden name=data[p_num1] value='{$aData.p_num1|escape}'>
<input type=hidden name=data[site] value='{$aData.site|escape}'>
<input type=hidden name=data[id_parent] value='{$idParent|escape}'>

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>