<td>{$aRow.name}</td>
<td>{$aRow.type_}</td>
<td>{$aRow.delimiter}</td>
<td>{$aRow.row_start}</td>
<td>{$aRow.col_cat}</td>
<td>{$aRow.col_code}</td>
<td>{$aRow.col_cat_crs}</td>
<td>{$aRow.col_code_crs}</td>
<td>{$aRow.charset}</td>
<td align=right><nobr>
<a href="/?action=catalog_cross_profile_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Edit")}</a>

<a href="/?action=catalog_cross_profile_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Delete")}</a>
</td>
