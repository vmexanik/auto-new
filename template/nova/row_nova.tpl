{assign var=responce value=$aRow.responce|unserialize}
<td>{$aRow.id_en}</td>
<td><a href="/?action=manager_package_edit&id={$aRow.order_id}">{$aRow.order_id}</a></td>
<td>{$aRow.post_date}</td>
{if $responce.success}
<td nowrap="nowrap">
<a target="_blank" href="https://my.novaposhta.ua/orders/printDocument/orders[]/{$responce.data.0.Ref}/type/pdf/apiKey/{$oLanguage->GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7')}">Скачать ЕН</a>
<br>
<a target="_blank" href="https://my.novaposhta.ua/orders/printMarkings/orders[]/{$responce.data.0.Ref}/type/pdf/apiKey/{$oLanguage->GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7')}">Скачать Наклейку</a>
</td>
{/if}

<td><a target="_blank" href="https://my.novaposhta.ua/scanSheet/printScanSheet/refs[]/{$aRow.reestr_key}/type/pdf/apiKey/{$oLanguage->GetConstant('novaposhta:key','40526d7535b7e92437586008d93c7da7')}">{$aRow.reestr}</a></td>
<td>{if !$aRow.reestr}<a href="/?action=novaposhta_delete_reestr&id_en={$aRow.id_en}&return={$sReturn|escape:"url"}" 
onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
>
<img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/></a>
{/if}
</td>