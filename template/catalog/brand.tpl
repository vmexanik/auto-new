<table>
<tr><td width="20%"><b style="color:#8EBB2B">{$oLanguage->getMessage("Name Brand")}</b></td><td><b>{$aRow.title}</b>
		{*if $aAuthUser.type_=='manager'}
<a target="_blank" href="/?action=catalog_manager_edit_brand&pref={$aRow.pref}&return={$sReturn|escape:"url"}">
<img src="/image/design/edit.png" title="edit"></a>
		{/if*}
</td>
<td rowspan="3">{if $aRow.image}<img style="max-width:100px;max-height:100px;" src="{$aRow.image}" alt="{$aRow.title}" title="{$aRow.title}">{/if}
		{*if $aAuthUser.type_=='manager'}
<a target="_blank" href="/?action=catalog_manager_edit_pic_brand&pref={$aRow.pref}&return={$sReturn|escape:"url"}">
<img src="/image/design/edit.png" title="edit"></a>
		{/if*}
</td></tr>
{if $aRow.country}<tr><td width="20%"><b style="color:#8EBB2B">{$oLanguage->getMessage("country")}</b></td><td><b>{$aRow.country}</b></td></tr>{/if}
{if $aRow.addres}<tr><td width="20%"><b style="color:#8EBB2B">{$oLanguage->getMessage("Addres")}</b></td><td><b>{$aRow.addres}</b></td></tr>{/if}
{if $aRow.link}<tr><td width="20%"><b style="color:#8EBB2B">{$oLanguage->getMessage("Site")}</b></td><td><b><a href="http://{$aRow.link}" rel="nofollow" target="_blank">{$aRow.link}</a></b></td></tr>{/if}
{if $aRow.descr}<tr><td width="20%" colspan="3">{$aRow.descr}</td></tr>{/if}
</table>