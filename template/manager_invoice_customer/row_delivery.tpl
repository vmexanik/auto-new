{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='weight'}
<td>
{$aRow.weight} / <b{if !$aRow.correct_weight} style="color:red;"{/if}>{$aRow.correct_weight}</b><br>
<input id="correct_weight{$aRow.id}" type=text value="{$aRow.correct_weight}" style="width:41px">
<input type="button" class='at-btn' value="{$oLanguage->getMessage('change')}" 
{strip}onclick="location.href="/?action=manager_invoice_customer_delivery&id={$aRow.id}
&id_user={$smarty.request.id_user}&correct_weight='+$('#correct_weight{$aRow.id}').val();
"{/strip}>
</td>

{elseif $sKey=='number'}
<td>{$aRow.number} / {$aRow.count}</s></td>

{elseif $sKey=='price_total'}
<td><br>{$oLanguage->PrintPrice($aRow.part_cost)}<s>{$oLanguage->PrintPrice($aRow.price_total)}</s></td>

{elseif $sKey=='price_tax'}
<td>
{$oLanguage->PrintPrice($aRow.delivery_cost_tax)}
<br>*{$aRow.tarif_tax}
</td>

{elseif $sKey=='total'}
<td><b>{$oLanguage->PrintPrice($aRow.total)}</b></td>
{elseif $sKey=='weight_delivery_cost'}
<td>
{$oLanguage->PrintPrice($aRow.weight_delivery_cost)} / 
<b>{$oLanguage->PrintPrice($aRow.correct_weight_delivery_cost)}</b>
<br>{$oLanguage->GetMessage('Tarif')}: {$aRow.weight_tarif}
</td>

{else}
<td>{$aRow.$sKey}</td>
{/if}
{/foreach}