<tr align="left">
	<td><input id="rName" type="text" name="data[crit_name]"  style="width:250px;" value="" ></td>
	<td><input id="rCode" type="text" name="data[crit_code]"  style="width:250px;" value="" ></td>
	<td><input id="rat-btn" class="at-btn" type="button" name="at-btn" maxlength="12" style="height:40px;  line-height: 40px;" value="{$oLanguage->GetMessage('Add')}" 
		onclick="{strip}
			location.href='?action=catalog_manager_add_info&data[id_cat_part]={$aData.id_cat_part}
			&data[name]='+$('#rName').val()+'
			&data[code]='+$('#rCode').val()+'
			&data[at-btn]='+$('#rat-btn').val()+'
			&return={$sReturn|escape:"url"}'
		{/strip}"></td>
</tr>