<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Provider')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('FaqCategoryName')}:</td>
   <td>{html_options name=data[id_faq_category] options=$aFaqCategory selected=$aData.id_faq_category}
   </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Question')}:{$sZir}</td>
   <td><textarea name=data[question] rows="3">{$aData.question}</textarea></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Answer')}:{$sZir}</td>
   <td><textarea name=data[answer] rows="3">{$aData.answer}</textarea></td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('Visible')}:</td>
   <td><input type="hidden" name=data[visible] value="0">
   <input type=checkbox name=data[visible] value='1' style="width:22px;" {if $aData.visible}checked{/if}></td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Num')}:</td>
   <td><input type=text name=data[num] value="{$aData.num|escape}"></td>
</tr>
</table>

</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>