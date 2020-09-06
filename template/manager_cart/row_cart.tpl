<td>
    <div class="order-num">{$oLanguage->GetMessage('User')}</div>
    {assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
    {$oLanguage->AddOldParser('customer_uniq',$Id)}
    <br>
    {$aRow.user_post_date}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Code')}</div>
    {$aRow.code}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('brand')}</div>
    {$aRow.cat_name}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Name')}</div>
    <div style="width:200px;overflow:overlay;">
	{if $aRow.is_archive} <font color=silver>{/if}{$aRow.name}
	</div>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('number')}</div>
    {$aRow.number}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('price')}</div>
    {$oCurrency->PrintPrice($aRow.price)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    <nobr>{$aRow.post_date}</nobr>
</td>
<td nowrap>
    <a href="/?action=manager_cart_delete&id={$aRow.id}"
    	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
    	><img src="/image/delete.png" border=0  width=16 align=absmiddle /></a>
</td>