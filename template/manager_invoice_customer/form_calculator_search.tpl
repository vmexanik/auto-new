<script type="text/javascript" src="/js/calculator.js"></script>

<table width=740 border=0>
<tr>
	<td colspan=3>
		{*<b>{$oLanguage->GetMessage("Region from")}:{$sZir}</b>
		<select  style="width:120px" id=id_provider_region name=search[id_provider_region]
		{strip}onChange="xajax_process_browse_url('?action=calculator_zone_change_form
		&id_provider_region='+$('#id_provider_region').val()+'
		&id_provider_region_to='+$('#id_provider_region_to').val()+'
		&id_provider_region_way='+$('#id_provider_region_way').val()+'
		&code_type='+$('#code_type').val()+'
		&id_payment_mode='+$('#id_payment_mode').val()
		);">{/strip}
   		{html_options options=$aProviderRegionAssoc
   		selected=$smarty.request.search.id_provider_region }
		</select>*}
		
		<b>{$oLanguage->GetMessage("Region to")}:{$sZir}</b>
   		<select  style="width:120px" id=id_provider_region_to name=search[id_provider_region_to]
   		{strip}onChange="xajax_process_browse_url('?action=calculator_zone_change_form
		&id_provider_region='+$('#id_provider_region').val()+'
		&id_provider_region_to='+$('#id_provider_region_to').val()+'
		&id_provider_region_way='+$('#id_provider_region_way').val()+'
		&code_type='+$('#code_type').val()+'
		&id_payment_mode='+$('#id_payment_mode').val()
		);">{/strip}
   		{html_options options=$aProviderRegionTo
   		selected=$smarty.request.search.id_provider_region_to}
		</select>
		{if $sNameCountry}<font color="blue">{$sNameCountry}</font>{/if}
		
		<!--b>{$oLanguage->GetMessage("Way")}:{$sZir}</b>
   		<select id=id_provider_region_way name=search[id_provider_region_way]
		{strip}onChange="xajax_process_browse_url('?action=calculator_change_form
		&id_provider_region='+$('#id_provider_region').val()+'
		&id_provider_region_to='+$('#id_provider_region_to').val()+'
		&id_provider_region_way='+$('#id_provider_region_way').val()+'
		&id_payment_mode='+$('#id_payment_mode').val()
		);">{/strip}
   		{html_options options=$oLanguage->GetMessageArray($aTypeDelivery) selected=$smarty.request.search.id_provider_region_way}
		</select>
		
		<b>{$oLanguage->GetMessage("Payment mode")}:{$sZir}</b>
   		<select id=id_payment_mode name=search[id_payment_mode]
		{strip}onChange="xajax_process_browse_url('?action=calculator_change_form
		&id_provider_region='+$('#id_provider_region').val()+'
		&id_provider_region_to='+$('#id_provider_region_to').val()+'
		&id_provider_region_way='+$('#id_provider_region_way').val()+'
		&id_payment_mode='+$('#id_payment_mode').val()
		);">{/strip}
   		{html_options options=$oLanguage->GetMessageArray($aPaymentMode) selected=$smarty.request.search.id_payment_mode}
		</select-->
		
		&nbsp;&nbsp;&nbsp;&nbsp;
		<b>{$oLanguage->GetMessage("Type delivery")}:{$sZir}</b>
   		<select id=code_type name=search[code_type]
		{strip}onChange="xajax_process_browse_url('?action=calculator_zone_change_form
		&id_provider_region='+$('#id_provider_region').val()+'
		&id_provider_region_to='+$('#id_provider_region_to').val()+'
		&id_provider_region_way='+$('#id_provider_region_way').val()+'
		&code_type='+$('#code_type').val()+'
		&id_payment_mode='+$('#id_payment_mode').val()
		);">{/strip}
   		{html_options options=$aCodeType selected=$smarty.request.search.code_type}
		</select>
	</td>
</tr>
<tr>
	<td colspan=3><hr /></td>
</tr>
<tr>

	<td width=20%><b>{$oLanguage->GetMessage("Width")}:{$sZir}</b>
		<input type=text id='search_width_id' name=search[width] value='{$smarty.request.search.width}'
				maxlength=10 style='width:35px' onKeyUp="oMstarCalculator.ChangeVolume()"
				>&nbsp;{$oLanguage->GetMessage("sm")}</td>
	<td width=20%><b>{$oLanguage->GetMessage("Length")}:{$sZir}</b>
		<input type=text id='search_length_id' name=search[length] value='{$smarty.request.search.length}'
				maxlength=10 style='width:35px' onKeyUp="oMstarCalculator.ChangeVolume()"
				>&nbsp;{$oLanguage->GetMessage("sm")}</td>
	<td width=20%><b>{$oLanguage->GetMessage("Height")}:{$sZir}</b>
		<input type=text id='search_height_id' name=search[height] value='{$smarty.request.search.height}'
				maxlength=10 style='width:35px' onKeyUp="oMstarCalculator.ChangeVolume()"
				>&nbsp;{$oLanguage->GetMessage("sm")}</td>
</tr>


<tr>
	<td width=30%><b>{$oLanguage->GetMessage("Weight")}:{$sZir}</b>
	<input type=hidden name=search[weight] value='{$smarty.request.search.weight}'>
		&nbsp;{$smarty.request.search.weight}&nbsp;{$oLanguage->GetMessage("kg")}</td>

	<td width=30%><b>{$oLanguage->GetMessage("Volume weight")}</b>
		{$oLanguage->GetContextHint('volume weight')}:
		<font color=green size=2><b><span id='volume_weight_id'>{$aResult.volume_weight}</span></b></font>
	</td>

	<td width=30%><b>{$oLanguage->GetMessage("Volume")}</b>:
		<font color=green size=2><b><span id='volume_id'>{$aResult.volume}</span>&nbsp;m<sup>3</sup></b></font>
	</td>
</tr>
<tr>
	<td colspan=3><b>{$oLanguage->GetMessage("info")}:</b>
		<b><font color=blue><span id='provider_region_description_id'>{$aProviderRegion.description}</span></font></b>
	</td>
</tr>
<tr>
	<td colspan=3><hr /></td>
</tr>

<tr>
	<td colspan=3>
		{if $aResult.cost1}
		{$oLanguage->GetMessage("cost1")}: {$oLanguage->PrintPrice($aResult.cost1)}
		&nbsp;
		{$oLanguage->GetMessage("vat")}: {$oLanguage->PrintPrice($aResult.tax1)}
		<br>
		{/if}
		{if $aResult.cost2}
		{$oLanguage->GetMessage("cost2")}: {$oLanguage->PrintPrice($aResult.cost2)}
		&nbsp;
		{$oLanguage->GetMessage("vat")}: {$oLanguage->PrintPrice($aResult.tax2)}
		<br>
		{/if}
		<b>{$oLanguage->GetMessage("delivery cost")}:</b>
		<font color=green size=2><b>
		<span id='delivery_cost_id'>
	{if $aResult.delivery_cost==-1}
		{$oLanguage->GetText('Calculator not implemented yet or delivery cost is zero')}
	{else}
		{$oLanguage->PrintPrice($aResult.delivery_cost)}
	{/if}
		</span>
		</b></font>
		{if $aResult.cost}
		&nbsp;({$oLanguage->GetMessage("included")}
		&nbsp;{$oLanguage->GetMessage("vat")}: {$oLanguage->PrintPrice($aResult.tax)})
		{/if}
		<br><b>{$oLanguage->GetMessage("Manual cost")}:</b>
		<input type=text id='search_manual_id' name=search[manual] value='{$smarty.request.search.manual}'
				maxlength=10 style='width:35px'
				>
		</td>
</tr>
</table>