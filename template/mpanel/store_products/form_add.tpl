<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('store products')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width=50%>{$oLanguage->getDMessage('Pref')}:{$sZir}</td>
				<td>{html_options name=data[pref] options=$aPref selected=$aData.pref}</td>
			</tr>
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
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> 
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>