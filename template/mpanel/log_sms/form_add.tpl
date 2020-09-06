<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Compose SMS')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Phone Number')}:{$sZir}</td>
				<td><input type=text name=data[number] value="{$aData.number|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Message')}:{$sZir}</td>
				<td><textarea name=data[message] onkeyup="this.value=this.value.substr(0,70);document.getElementById('sms_chars_left').innerHTML=70-this.value.length">{$aData.message}</textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>{$oLanguage->getDMessage('Chars left')}: <span id="sms_chars_left">70</span></td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> {include
file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>