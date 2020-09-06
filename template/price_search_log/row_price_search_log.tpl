<td>
    <div class="order-num">{$oLanguage->GetMessage('Make')}</div>
    {$aRow.cat_name}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Code')}</div>
    {$aRow.code}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Date')}</div>
    {$aRow.post_date}
</td>
<td>
    <a href="/?action=catalog_price_view&code={$aRow.code}{if $aRow.pref}&pref={$aRow.pref}{/if}">
    {$oLanguage->getMessage('View')}</a>
</td>