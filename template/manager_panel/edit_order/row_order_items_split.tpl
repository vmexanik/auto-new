{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='term'}
<td> {$aRow.term}</td>
{elseif $sKey=='price'}
<td>{$oCurrency->PrintPrice($aRow.price,0,0,'<none>')}</td>
{elseif $sKey=='price_total'}
<td>{$oCurrency->PrintPrice($aRow.price_total,0,0,'<none>')}</td>
{elseif $sKey=='brand'}
<td>{if $aRow.cat_name_changed}{$aRow.cat_name_changed}{else}{$aRow.brand}{/if}</td>
{elseif $sKey=='code'}
<td>{if $aRow.code_changed}{$aRow.code_changed}{else}{$aRow.code}{/if}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}