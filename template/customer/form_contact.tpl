<table>
	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("Delivery Type")}:</b></td>
   		<td>
   		<select name='code_delivery_type' style='width:270px'>
		{section name=d loop=$aDeliveryType}
		<option value={$aDeliveryType[d].code}
			{if $aDeliveryType[d].code == $aData.code_delivery_type} selected {/if}
				> {$aDeliveryType[d].name}</option>
		{/section}
		</select>
   		</td>
  	</tr>
	<tr>
   		<td width=50%><b>{$oLanguage->getMessage("FLName")}:</b></td>
   		<td><input type=text name=name value='{$aData.name}' maxlength=20 style='width:270px'></td>
  	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Address")}:</b></td>
		<td><textarea name=address style='width:270px'>{$aData.address}</textarea></td>
	</tr>
	<tr>
		<td><b>{$oLanguage->getMessage("Phone")}:</b></td>
		<td><input type=text name=phone value='{$aData.phone}' maxlength=32 style='width:270px'></td>
	</tr>
</table>