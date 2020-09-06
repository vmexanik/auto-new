<td class="cell-code">{if $aRow.code_visible}
		{$aRow.code}
	{else}
		<i>{$oLanguage->getMessage("cart_invisible")}</i>
	{/if}
	<br><b>{$aRow.cat_name}</b>
</td>
<td class="cell-name"><div style="{* width:320px; *}overflow:overlay;">
    {if $aRow.is_archive} <font color=silver>{/if}
	{$oContent->PrintPartName($aRow)}

	<br><font color=red>{$aRow.customer_id}</font>


{if $aRow.price_request_id}
<br>
<font color=red>{$oLanguage->GetMessage('price request date')}: {$aRow.price_request_post_date}
: <b>{$aRow.price_request_status_name}
</b></font>
{/if}
<br><font color="#9B9B9B">{$aRow.customer_comment}</font>
</div>
</td>
<td class="cell-date">{$aRow.post_date}</td>
<td class="cell-number"><input type='text' id='cart_{$aRow.id}' name='cart[{$aRow.id}]' value='{$aRow.number}'
		maxlength=3 style='width: 20px'
	onKeyUp="xajax_process_browse_url('?action=cart_cart_update_number&id={$aRow.id}&number='+this.value);"
	>
</td>

<td class="cell-weight"><nobr>{$aRow.weight} {$oLanguage->GetMessage('kg')} </nobr>
</td>
<td class="cell-price">{$oCurrency->PrintPrice($aRow.price)} </td>
<td class="cell-total"><span id='cart_total_{$aRow.id}'>{$oCurrency->PrintSymbol($aRow.total)}</span>
</td>
<td class="cell-action" nowrap>


<a href="/?action=cart_cart_edit&id={$aRow.id}"><img src="/image/edit.png" border=0 width=16 align=absmiddle /> {$oLanguage->getMessage("Your comments")}</a>
<br>

<a href="/?action=cart_cart_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle /> {$oLanguage->getMessage("Delete")}</a>

</td>
