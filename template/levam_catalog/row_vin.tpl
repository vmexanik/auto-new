<td>
    <div class="order-num">{$oLanguage->GetMessage('Марка')}</div>
    {$aRow.make}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Регион')}</div>
    {$aRow.model}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Руль')}</div>
    {$aRow.description}
</td>
<td>
    <a href="/pages/levam_group?ssd={$sSSD}&link={$aRow.link}"> --> </a>
</td>