<td>{$aRow.id}</td>
<td>{$aRow.brand}</td>
<td><a href="/buy/{$aRow.c_name}_{$aRow.code}">{$aRow.code}</a></td>
<td><img src="{$aRow.image}" style="max-height: 50px; max-width: 50px"></td>
<td>
<a href="/?action=extension_td_history_image_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
><img src="/image/delete.png" border=0 width=16 align=absmiddle /></a>
</td>