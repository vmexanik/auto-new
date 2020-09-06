<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.standart.part_number}
</td>
<td style="white-space:nowrap;">
    <div class="order-num">{$oLanguage->GetMessage('Код')}</div>
    <a href="/price/{$aRow.code}/" >{$aRow.standart.part_code}</a>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Название')}</div>
    {$aRow.standart.part_name}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Информация')}</div>
    {$aRow.add.info}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Количество')}</div>
    {$aRow.standart.part_quantity}
</td>