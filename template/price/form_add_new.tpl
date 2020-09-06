<table cellspacing=2 cellpadding=1>
	<tr>
		<td><b>{$oLanguage->getDMessage('Provider')}:{$sZir}</b></td>
		<td>{html_options name=data[id_provider] options=$aProviders selected=$aData.id_provider style="width: 100% !important; max-width:230px"}</td>
	</tr>			
	{*<tr>
		<td><b>{$oLanguage->getDMessage('price group')}:{$sZir}</b></td>
		<td>{html_options  name=data[id_price_group] options=$aPriceGroup selected=$aData.id_price_group style="width: 100% !important; max-width:230px"}</td>
	</tr>*}
	<tr>
		<td><b>{$oLanguage->getDMessage('Brand')}:{$sZir}</b></td>
		<td>
		    <select name="data[pref]" style="width: 100% !important; max-width:230px">
		    {foreach from=$aCat item=aItem}
                <option label="{$aItem.name}" value="{$aItem.pref}">{$aItem.name}</option>
            {/foreach}
            </select>
	<tr>
		<td><b>{$oLanguage->getDMessage('code')}:{$sZir}</b></td>
		<td><input type=text name=data[code] style="width: 100% !important; max-width:270px"
			value="{$aData.code|escape}"></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getDMessage('price')}:</b></td>
		<td><input type=text name=data[price] style="width: 100% !important; max-width:270px"
			value="{$aData.price|escape}"></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getDMessage('name')}:</b></td>
		<td><input type=text name=data[part_rus] style="width: 100% !important; max-width:270px"
			value="{$aData.part_rus|escape}"></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getDMessage('term')}:</b></td>
		<td><input type=text name=data[term] style="width: 100% !important; max-width:270px"
			value="{$aData.term|escape}"></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getDMessage('stock')}:</b></td>
		<td><input type=text name=data[stock] style="width: 100% !important; max-width:270px"
			value="{$aData.stock|escape}"></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getDMessage('information')}:</b></td>
		<td><textarea rows="5" cols="20" name=data[description]>{$aData.description|escape}</textarea></td>
	</tr>
</table>