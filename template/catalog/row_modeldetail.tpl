<td class="cell-name">{if $smarty.request.form_hide}<b>{$aRow.name}</b> {else}
<a href="{if $aRow.seourl}{$aRow.seourl}{else}/cars/{$aRow.cat_name}/{$aRow.id_model}-{$aRow.id_model_detail}{/if}">
{$aRow.name}</a>{/if}</td>
<td class="cell-years" nowrap>{$aRow.month_start}.{$aRow.year_start} - {if !$aRow.year_end}{$smarty.now|date_format:"%Y"}{else}{$aRow.month_end}.{$aRow.year_end}{/if}</td>
<td class="cell-power-kw" align=center>{$aRow.kw_from}</td>
<td class="cell-power-hp" align=center>{$aRow.hp_from}</td>
<td class="cell-volume" align=center>{$aRow.ccm}</td>
<td class="cell-body">{$aRow.body}</td>
<!--<td>{$aRow.axis}</td>
<td>{$aRow.max_weight}</td>
<td>{$aRow.body_des_id}</td>-->
{*<td class="cell-fuel">$oLanguage->getMessage($aRow.engine_des_id)
{if $aRow.engine_des_id==$oLanguage->GetConstant('catalog:code_gasoline')}{$oLanguage->getMessage("gasoline")}{/if}
{if $aRow.engine_des_id==$oLanguage->GetConstant('catalog:code_diesel')}{$oLanguage->getMessage("diesel")}{/if}
</td>*}
<!--<td>{$aRow.axis_des_id}</td>
<td>{$aRow.mod_id}</td>
<td>{$aRow.MOD_PC}</td>
<td>{$aRow.MOD_CV}</td>-->
<td>{$aRow.Fuel}</td>
<td>{$aRow.Engines}</td>