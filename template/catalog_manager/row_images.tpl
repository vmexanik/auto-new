<tr>
<td>{$aRow.id}</td>
<td>{$aRow.make}</td>
<td>{$aRow.pref}</td>
<td><a href='http://{$smarty.server.SERVER_NAME}/?action=catalog_part_info_view&code={$aRow.code}&id_brand={$aRow.id_tof}&item_code={$aRow.item_code}&id_provider={$aRow.id_provider}&return={$sReturn|escape:"url"}'>{$aRow.code}</a></td>
<td>{$aRow.pic}</td>
<td>{$aRow.extension}</td>
<td nowrap>
<a href="http://{$smarty.server.SERVER_NAME}/?action=catalog_manager_delete_pic&id={$aRow.id}&return={$sReturn|escape:"url"}"
onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
><img src="/image/delete.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Delete")}</a>
</td>
</tr>