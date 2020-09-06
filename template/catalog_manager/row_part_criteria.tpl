<td>
	{$aRow.krit_name}
	{if $aRow.id_cat_info}
		<br>
		{$oLanguage->getMessage('ua')}: <label id='krit_name_{$aRow.id_cat_info}' style="color: green;">{$aRow.krit_name_ua}</label><a href="#" onclick="$('#krit_name_div_{$aRow.id_cat_info}').toggle(); return false;"><img src="/image/edit.png"></a>
		<div id="krit_name_div_{$aRow.id_cat_info}" class="krit">
			<input type="text" id="krit_name_edit_{$aRow.id_cat_info}" value="{$aRow.krit_name_ua}">
			<input type="button" value="Ok" onclick="xajax_process_browse_url('/?action=catalog_manager_change_krit&id={$aRow.id_cat_info}&krit_name='+$('#krit_name_edit_{$aRow.id_cat_info}').val()); $('#krit_name_div_{$aRow.id_cat_info}').hide(); return false;">
		</div>
	{/if}
</td>
<td>
	{$aRow.krit_value}
	{if $aRow.id_cat_info}
		<br>
		{$oLanguage->getMessage('ua')}: <label id='krit_value_{$aRow.id_cat_info}' style="color: green;">{$aRow.krit_value_ua}</label><a href="#" onclick="$('#krit_value_div_{$aRow.id_cat_info}').toggle(); return false;"><img src="/image/edit.png"></a>
		<div id="krit_value_div_{$aRow.id_cat_info}" class="krit">
			<input type="text" id="krit_value_edit_{$aRow.id_cat_info}" value="{$aRow.krit_value_ua}">
			<input type="button" value="Ok" onclick="xajax_process_browse_url('/?action=catalog_manager_change_krit&id={$aRow.id_cat_info}&krit_value='+$('#krit_value_edit_{$aRow.id_cat_info}').val()); $('#krit_value_div_{$aRow.id_cat_info}').hide(); return false;">
		</div>
	{/if}
</td>
<td>{if $aRow.id_cat_info}<a href="http://{$smarty.server.SERVER_NAME}/?action=catalog_manager_delete_info&id={$aRow.id_cat_info}&return={$sReturn|escape:"url"}">
<img src="/image/delete.png" border=0  width=16 align=absmiddle /></a>
{else}&nbsp;{/if}
</td>

