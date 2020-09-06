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
				<td width="100%">{$oLanguage->getDMessage('id')}:{$sZir}</td>
				<td><input type=text name=data[id]
					value="{$aData.id|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('id_price_group')}:{$sZir}</td>
				<td><input type=text name=data[id_price_group]
					value="{$aData.id_price_group|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('id_provider')}:{$sZir}</td>
				<td><input type=text name=data[id_provider]
					value="{$aData.id_provider|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('code')}:{$sZir}</td>
				<td><input type=text name=data[code]
					value="{$aData.code|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('price')}:{$sZir}</td>
				<td><input type=text name=data[price]
					value="{$aData.price|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('part_rus')}:{$sZir}</td>
				<td><input type=text name=data[part_rus]
					value="{$aData.part_rus|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('pref')}:{$sZir}</td>
				<td><input type=text name=data[pref]
					value="{$aData.pref|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('cat')}:{$sZir}</td>
				<td><input type=text name=data[cat]
					value="{$aData.cat|escape}"></td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> 
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>