{if !$aResultPriceGroupSorted}
	{$oLanguage->GetMessage('Nothing found by sphinx search for phrase')}: "{$sQuery}"
{else}
	<h3>{$oLanguage->GetMessage('Sphinx search found such entries for phrase')} "{$sQuery}"
		{$oLanguage->GetMessage('in such categories')}:</h3>
	{foreach from=$aResultPriceGroupSorted item=aItem}
	<div style="padding: 10px 0 0 20px;">
		* <a href='/?action=search&search[query]={$sQuery}&search[id_price_group]={if $aItem.price_group.id}{$aItem.price_group.id}{else}-1{/if}'
			>{$aItem.price_group.name} ({$aItem.total_found}) </a>
	</div>
	{/foreach}
{/if}
<br>
<br>