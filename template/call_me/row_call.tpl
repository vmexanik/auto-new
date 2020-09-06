<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('fio')}</div>
    {$aRow.fio}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('phone')}</div>
    {$aRow.phone}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$aRow.post_date}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Resolution')}</div>
    {if $aRow.resolved}
    <span style="color:green">
    	{$oLanguage->getMessage('resolved')}
    </span>
    {else}
    <span style="color:red">
    	{$oLanguage->getMessage('not resolved')}
    </span>
    {/if}
    {if !$aRow.resolved}
    <br>
    <a href="/pages/call_me_show_manager/?id={$aRow.id}">{$oLanguage->getMessage('resolve')}</a>
    {/if}
</td>