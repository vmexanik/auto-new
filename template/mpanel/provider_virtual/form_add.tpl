<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Provider Virtual')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Provider')}:</td>
			   <td>{html_options name=data[id_provider] options=$aProvider selected=$aData.id_provider}
			   </td>
			</tr>

			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Provider virtual')}:</td>
			   <td>{html_options name=data[id_provider_virtual] options=$aProvider selected=$aData.id_provider_virtual}
			   </td>
			</tr>
			
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>