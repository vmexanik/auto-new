{foreach key=sKey item=item from=$oTable->aColumn}
<td>
<div class="order-num">{$item.sTitle}</div>
{if ($sKey == 'price')}
    {$oCurrency->PrintSymbol($aRow.$sKey,$aRow.id_currency)}
{else}
    {if $sKey=='action'}
	<a href="/?action=payment_report_edit&id={$aRow.id}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1 alt="{$oLanguage->getMessage('edit')}" title="{$oLanguage->getMessage('edit')}"/></a>
	<br>

	<a href="/?action=payment_report_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1 alt="{$oLanguage->getMessage('delete')}" title="{$oLanguage->getMessage('delete')}"/></a>
    {else}
	{$aRow.$sKey}
    {/if}
{/if}
</td>
{/foreach}