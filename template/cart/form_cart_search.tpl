<table width=100% border=0>
	<!--tr>
		<td width=50% colspan=2><b>{$oLanguage->getMessage("Vin Request")}:</b>
		&nbsp;<select name='search_id_vin_request' >
			<option value='0'>{$oLanguage->getMessage("Show All")}</option>
			{foreach from=$aVinRequest item=aItem}
			<option value='{$aItem.id}' {if $smarty.request.search_id_vin_request==$aItem.id}selected{/if}
				>{$aItem.id} - {$aItem.marka}</option>
			{/foreach}
			</select>
		</td>
		<td width=50%>&nbsp;</td>
	</tr-->
	<tr>
		<td width=50%><b>{$oLanguage->getMessage("CartCode")}:</b>
		&nbsp;<input type=text name=search_code value='{$smarty.request.search_code}' maxlength=20 style='width:135px'></td>
		<td width=50%><b>{$oLanguage->getMessage("Name")}:</b>
		<input type=text name=search_name value='{$smarty.request.search_name}' maxlength=20 style='width:135px'></td>
	</tr>
</table>