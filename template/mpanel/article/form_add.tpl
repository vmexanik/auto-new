<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">

<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Article')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('ArticleCategoryName')}:</td>
   <td>{html_options name=data[id_article_category] options=$aArticleCategoryHash selected=$aData.id_article_category}
   </td>
</tr>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
   <td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Translation')}:</td>
	<td>{$oAdmin->getCKEditor('data[content]',$aData.content)}</td>
</tr>
{include file='addon/mpanel/form_visible.tpl' aData=$aData}
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