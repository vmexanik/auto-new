{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='tax'}
<td class="col_right">{$oCurrency->PrintPrice($aRow.tax)}</td>
{elseif $sKey=='price'}
<td class="col_right">{$oCurrency->PrintPrice($aRow.price)}</td>
{elseif $sKey=='count'}
<td class="col_right">{$aRow.count|number_format:2}</td>
{elseif $sKey=='id'}
<td class="col_right">{$aRow.id}</td>
{elseif $sKey=='id_order'}
<td class="col_right">{$aRow.id_order}</td>
{elseif $sKey=='id_user'}
<td class="col_right">{$aRow.id_user}</td>
{else}<td class="col_left">{$aRow.$sKey}</td>{/if}
{/foreach}