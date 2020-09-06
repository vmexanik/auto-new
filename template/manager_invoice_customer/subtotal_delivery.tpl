<tr class="even"><td colspan=6><hr></td></tr>
<tr class="even">
<td><b>{$oLanguage->GetMessage('Subtotal')}:</b></td>
<td><b>{$aSubtotal.number} / {$aSubtotal.count}</b></td>
<td><b>{$aSubtotal.correct_weight}</b></td>
<td><b>{$oLanguage->PrintPrice($aSubtotal.correct_weight_delivery_cost)}</b></td>
<td><b>{$oLanguage->PrintPrice($aSubtotal.delivery_cost_tax)}</b></td>
<td><b>{$oLanguage->PrintPrice($aSubtotal.total)}</b></td>
</tr>