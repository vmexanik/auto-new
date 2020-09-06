<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<table cellspacing=0 cellpadding=2 class=add_form>
<tr>
 <th>
 {$oLanguage->getDMessage('Price group')}
 </th>
</tr>
<tr><td>

<table cellspacing=2 cellpadding=1>
<tr>
   <td width=50%>{$oLanguage->getDMessage('Code')}:{$sZir}</td>
   <td><input type=text name=data[code] value="{$aData.code|escape}"></td>
</tr>
<tr>
	<td>{$oLanguage->getDMessage('title')}:</td>
	<td><input type=text name=data[title] value="{$aData.title|escape}"></td>
</tr>
<tr>
	<td>{$oLanguage->getDMessage('page_description')}:</td>
	<td><textarea name=data[page_description] rows='5'>{$aData.page_description|escape}</textarea></td>
</tr>
<tr>
	<td>{$oLanguage->getDMessage('page_keyword')}:</td>
	<td><textarea name=data[page_keyword] rows='5'>{$aData.page_keyword|escape}</textarea></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Description')}:</td>
	<td>{$oAdmin->getCKEditor('data[description]',$aData.description)}</td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('is_product_list_visible')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_product_list_visible'
   		bChecked=$aData.is_product_list_visible}</td>
  </tr>

{include file='addon/mpanel/form_visible.tpl' aData=$aData}
</table>
</td></tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
</FORM>
