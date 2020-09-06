<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Provider Pref')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Provider')}:{$sZir}</td>
			   <td>{html_options name=data[id_user_provider] options=$aProvider selected=$aData.id_user_provider}
			   </td>
			</tr>

			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Pref')}:{$sZir}</td>
			   <td>{html_options name=data[pref] options=$aPref selected=$aData.pref}
			   </td>
			</tr>
			
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Email to')}:{$sZir}</td>
			   <td><input type=text name=data[mail_to] value="{$aData.mail_to|escape}"></td>
			</tr>
			
			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Name to')}:{$sZir}</td>
			   <td><input type=text name=data[name_to] value="{$aData.name_to|escape}"></td>
			</tr>

			<tr>
			   <td width=50%>{$oLanguage->getDMessage('Subject')}:{$sZir}</td>
			   <td><input type=text name=data[subject] value="{$aData.subject|escape}"></td>
			</tr>
			
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</FORM>