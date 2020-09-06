<td>
    <div class="order-num">{$oLanguage->GetMessage('Name')}</div>
    {$aRow.name}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Date')}</div>
    {$aRow.last_date_work|date_format:"%d.%m.%Y %H:%M"}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('FileName on mail')}</div>
    {$aRow.file_name}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('email')}</div>
    {$aRow.email}
</td>
<td nowrap>
    <a href="/?action=price_profile_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
    	><img src="/image/edit.png" border=0 width=16 align=absmiddle hspace=1/></a>
    <br>
    <a href="/?action=price_profile_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
    	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
    	><img src="/image/delete.png" border=0  width=16 align=absmiddle hspace=1/></a>
</td>
