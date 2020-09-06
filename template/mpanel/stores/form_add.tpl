<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Price')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('code')}:{$sZir}</td>
				<td><input type=text name=data[code]
					value="{$aData.code|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('name')}:{$sZir}</td>
				<td><input type=text name=data[name]
					value="{$aData.name|escape}"></td>
			</tr>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Description')}:</td>
			   <td><textarea name=data[description]>{$aData.description}</textarea></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('provider')}:</td>
				<td>{html_options name=data[id_provider] options=$aProviders selected=$aData.id_provider}</td>
			</tr>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('is_virtual')}:</td>
			   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_virtual' bChecked=$aData.is_virtual}</td>
			</tr>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('is_return')}:</td>
			   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_return' bChecked=$aData.is_return}</td>
			</tr>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('is_sale')}:</td>
			   <td>{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_sale' bChecked=$aData.is_sale}</td>
			</tr>
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> 
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>