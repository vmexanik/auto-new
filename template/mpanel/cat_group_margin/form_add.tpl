<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Cat Group Margin')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width=50%>{$oLanguage->getDMessage('Name')}:{$sZir}</td>
				   <td><input readonly type=text name=data[name] value="{$aData.name|escape}" style="width:800px"></td>
			</tr>
			<tr>
				<td width=50%>{$oLanguage->getDMessage('Margin')} %:</td>
				   <td><input type=text name=data[margin] value="{$aData.margin|escape}"></td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}