<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Currencies')}</th>
	</tr>
	<tr>
		<td>
		<table cellspacing=2 cellpadding=1 width=700>
			<tr>
				{foreach from=$aCurrency item=aValue}
				<td>{$aValue.code}({$aValue.value}) <input type='text'
					name=data[currency_{$aValue.code}] value='' maxlength=8
					style='width: 50px'
					onKeyUp="document.getElementById('amount').value= Math.round(this.value/{$aValue.value} *100)/100;
					document.getElementById('code_currency').value= '{$aValue.code}';
					"></td>
				{/foreach}
			</tr>
		</table>
		</td>
	</tr>
</table>


<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('move account money')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width=100%>{$oLanguage->getDMessage('Amount')}:{$sZir}</td>
				<td colspan="3"><input type=text id="amount" name=data[amount] value="0"
					readonly style="width: 70">

				&nbsp;&nbsp;&nbsp;{$oLanguage->getDMessage('Currency')}: <input
					type=text name=data[code_currency] id='code_currency' value=""
					readonly style="width: 30"></td>
			</tr>

			<tr>
				<td>{$oLanguage->getDMessage('ID Account')}: {$sZir}</td>
				<td>{html_options name=data[id_subconto1_from] options=$aAccount}</td>
				<td>=></td>
				<td>{html_options name=data[id_subconto1_to] options=$aAccount}</td>
			</tr>

			<tr id="description_block">
				<td>{$oLanguage->getDMessage('Description')}:</td>
				<td colspan="3"><textarea name=data[description] rows=4></textarea></td>
			</tr>

		</table>

		</td>
	</tr>
</table>

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction} 
<input type=hidden name=action value={$sBaseAction}_move_apply></FORM>