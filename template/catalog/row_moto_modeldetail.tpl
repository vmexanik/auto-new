<td class="cell-name">
    <a href="/pages/catalog_moto_assemblage/?brand={$aRow.id_tof}&model={$aRow.id_model}&model_detail={$aRow.id_model_detail}">{$aRow.name}</a>
</td>
<td class="cell-years" nowrap>{$aRow.month_start}.{$aRow.year_start} - {$aRow.month_end}.{$aRow.year_end}</td>
<td class="cell-power-kw" align=center>{$aRow.kw_from}</td>
<td class="cell-power-hp" align=center>{$aRow.hp_from}</td>
<td class="cell-volume" align=center>{$aRow.ccm}</td>
<td class="cell-body">{$aRow.body}</td>
<!--<td>{$aRow.axis}</td>
<td>{$aRow.max_weight}</td>
<td>{$aRow.body_des_id}</td>-->
<td class="cell-fuel">{*$oLanguage->getMessage($aRow.engine_des_id)*}
{if $aRow.engine_des_id==$oLanguage->GetConstant('catalog:code_gasoline')}{$oLanguage->getMessage("gasoline")}{/if}
{if $aRow.engine_des_id==$oLanguage->GetConstant('catalog:code_diesel')}{$oLanguage->getMessage("diesel")}{/if}
</td>
<!--<td>{$aRow.axis_des_id}</td>
<td>{$aRow.mod_id}</td>
<td>{$aRow.MOD_PC}</td>
<td>{$aRow.MOD_CV}</td>-->