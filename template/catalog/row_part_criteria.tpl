<td>{$aRow.krit_name}</td>
<td>{if $aAuthUser.type_!='manager'}{$aRow.krit_value}{else}
{if !$aRow.params}
{$aRow.krit_value}
{else}
	<select name="param[{$aRow.id}]" onchange="xajax_process_browse_url('/?action=catalog_change_part_param&item_code={$aRowPrice.item_code}&param_id=id_{$aRow.table_}&param_value='+this.options[this.selectedIndex].value);">
	{html_options options=$aRow.params selected=$aRow.krit_selected style="width: 150px;"}
	</select>
{/if}
{/if}
</td>