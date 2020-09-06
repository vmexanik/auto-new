<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Order Status')}</div>
    {$oLanguage->getOrderStatus($aRow.order_status)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('VIN')}</div>
    {$aRow.vin}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Post')}</div>
    {$oLanguage->getPostDateTime($aRow.post_date)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Manager add Comment')}</div>
    {$aRow.manager_comment}
</td>
<td>
{if $aRow.order_status=='new' || $aRow.order_status=='refused'}
<a href="/?action=vin_request_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle title="{$oLanguage->GetMessage('Delete')}"/></a><br>
{/if}

{if $aRow.order_status=='parsed'}
<a href="/?action=vin_request_preview&id={$aRow.id}"
	><img src="/image/kviewshell.png" border=0  width=16 align=absmiddle title="{$oLanguage->GetMessage('Preview')}" /></a><br>
{/if}

<a href="/?action=vin_request_copy&id={$aRow.id}"
	><img src="/image/redo.png" border=0  width=16 align=absmiddle title="{$oLanguage->GetMessage('Copy')}" /></a>
</td>