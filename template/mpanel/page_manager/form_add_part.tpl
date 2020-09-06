<tr>
	<td width="100%">{$oLanguage->getDMessage('Name')}:{$sZir}</td>
	<td><input type=text name=data[name] value="{$aData.name|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Code')}:{$sZir}</td>
	<td><input type=text name=data[code] value="{$aData.code|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Order Num')}:</td>
	<td><input type=text name=data[num] value="{$aData.num|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Code is URL')}:</td>
	<td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='link' bChecked=$aData.link}</td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Invisible Map')}:</td>
	<td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='invisible_map' bChecked=$aData.invisible_map}</td>
</tr>
<tr>
   <td>{$oLanguage->getDMessage('Is Menu Visible')}:</td>
   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_menu_visible' bChecked=$aData.is_menu_visible}</td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Title')}:</td>
	<td><input type=text name=data[title] value="{$aData.title|escape}"></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Page Description')}:</td>
	<td><textarea name=data[page_description]>{$aData.page_description}</textarea></td>
</tr>
<tr>
	<td width="100%">{$oLanguage->getDMessage('Width Limit')}:</td>
	<td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='width_limit' bChecked=$aData.width_limit}</td>
</tr>

<tr>
	<td width="100%">{$oLanguage->getDMessage('Is Featured')}:</td>
	<td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_featured' bChecked=$aData.is_featured}</td>
</tr>

{include file='addon/mpanel/form_visible.tpl' aData=$aData}