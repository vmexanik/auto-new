{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td>
<div class="">
	<a href="/?action=price_control_unlocked_code&id={$aRow.id}&return={$sReturn|escape:"url"}"
		onclick="if (!confirm('{$oLanguage->getMessage("Are you sure?")}')) return false;"
		><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("unlock item")}" title="{$oLanguage->getMessage("unlock item")}" /> </a>
</div>
</td>
{elseif $sKey=='code_in'}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
	<p>{if $aRow.code_in}{$aRow.code_in}{/if}
</div>
</td>
{elseif $sKey=='cat_in'}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
	<p>{if $aRow.cat_in}{$aRow.cat_in}{/if}</p>
</div>
</td>
{elseif $sKey=='provider'}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
	<p>{if $aRow.provider_login}{$aRow.provider_login}{/if}</p>
	{if $aRow.name_provider && $aRow.name_provider!=$aRow.provider_login}<p>{$aRow.name_provider}</p>{/if}
</div>
</td>
{elseif $sKey=='date_set'}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
	<p>{$oLanguage->getPostDateTime($aRow.date_set)}</p>
</div>
</td>
{elseif $sKey=='id_manager'}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
	<p>{if $aRow.manager_name}{$aRow.manager_name}{else}{$aRow.manager_login}{/if}</p>
</div>
</td>
{else}
<td>
<div class="order-num">
<span>{$item.sTitle}</span>
<p>{$aRow.$sKey}</p>
</div>
</td>
{/if}
{/foreach}


