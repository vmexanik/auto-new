{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='term'}
<td> {$aRow.term}</td>
{elseif $sKey=='price_total'}
<td>{$oCurrency->PrintPrice($aRow.price_total,0,0,'<none>')}</td>
{elseif $sKey=='brand'}
<td>{if $aRow.cat_name_changed}{$aRow.cat_name_changed}{else}{$aRow.brand}{/if}</td>
{elseif $sKey=='code'}
<td>{if $aRow.code_changed}{$aRow.code_changed}{else}{$aRow.code}{/if}</td>
{elseif $sKey=='name_translate'}
<td>{$aRow.$sKey}</td>
{elseif $sKey=='number'}
<td>{$aRow.$sKey}</td>
{elseif $sKey=='price'}
<td>{$oCurrency->PrintPrice($aRow.price,0,0,'<none>')}</td>
{elseif $sKey=='action'}
<td>
	<a href="/?action=manager_panel_edit_order_set_status_refuse_item&id={$aCartPackage.id}&c_id={$aRow.id}" onclick="if (confirm('{$oLanguage->getMessage('are you sure to refuse cart item?')}'))
		xajax_process_browse_url(this.href); return false;">
		<span style="cursor:pointer;" class="glyphicon glyphicon-trash" aria-hidden="true" title="{$oLanguage->getMessage('delete')}"></span>
	</a>
</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}