{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td></td>
{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}