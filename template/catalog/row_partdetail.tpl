<td>{$aRow.art_id}<br></td>
<td>{$aRow.art_article_nr}</td>
<td>{$aRow.articledes}</td>
<td>{$aRow.ga_id}</td>
<td>{$aRow.gades}</td>
<td>{$aRow.ga_assembly}</td>
<td>{$aRow.la_id}</td>
<td>{$aRow.sort}</td>
<td>{$aRow.bra_id}</td>
<td>{$aRow.bra_brand}</td>
<td>{$aRow.sup_ids}</td>
<td><a href="/?action=tecdoc_detail_part_view&id_model_detal={$smarty.request.id_model_detal}&sup_id={$aRow.sup_id}&ga_nr={$aRow.ga_nr}&return={$sReturn|escape:"url"}">
<img src="/image/viewmagfit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("View Parts")}</a>
</td>




