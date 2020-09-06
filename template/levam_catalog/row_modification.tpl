<td>
    <div class="order-num">{$oLanguage->GetMessage('Марка')}</div>
    {$aRow.make}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Регион')}</div>
    {$aRow.region}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Руль')}</div>
    {$aRow.rudder}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Тип трансмиссии')}</div>
    {$aRow.trans}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Год производства')}</div>
    {$aRow.year}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Месяц производства')}</div>
    {$aRow.month}
</td>
<td>
    <a href="/pages/levam_group?ssd={$smarty.request.ssd}&link={$aRow.link}"> --> </a>
</td>