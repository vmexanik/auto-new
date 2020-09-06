<td>
	<div class="order-num">{$oLanguage->getMessage('#')}</div>
	{$aRow.row_id}
</td>
<td>
	<div class="order-num">{$oLanguage->getMessage('Date')}</div>
	{$aRow.post_date}
</td>
<td>
	<div class="order-num">{$oLanguage->getMessage('Customer Login')}</div>
	{$oLanguage->AddOldParser('customer',$aRow.id_user)}<nobr><font color=green><b>{$oLanguage->PrintPrice($aRow.current_account_amount)}</b></font></nobr>
</td>
<td nowrap>
	<div class="order-num">{$oLanguage->getMessage('DebtAmount')}</div>
	{$oLanguage->PrintPrice($aRow.debt_amount)}
</td>
<td>{if $aRow.amount<0}
		<div class="order-num">{$oLanguage->getMessage('finance credit')}</div>
		{$oLanguage->PrintPrice($aRow.amount)}
	{/if}
</td>
<td>{if $aRow.amount>=0}
		<div class="order-num">{$oLanguage->getMessage('finance debet')}</div>
		{$oLanguage->PrintPrice($aRow.amount)}
	{/if}
</td>
<td nowrap>
	<div class="order-num">{$oLanguage->getMessage('AccountAmount')}</div>
	{$oLanguage->PrintPrice($aRow.account_amount)}
</td>
<td>
	<div class="order-num">{$oLanguage->getMessage('Description')}</div>
{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b><br>{/if}
{$aRow.description}
<span id="div_view_comment_{$aRow.id}">
{if $aRow.data=='package_return' || (!$aRow.id_cart_package && $aRow.data!='prepay_customer' && $aRow.data!='debt_customer')}
	<br><span style="color:grey">Заказ № {$aRow.custom_id}</span>
{elseif $aRow.document}
	<br><span style="color:green">{$aRow.document}</span>
{/if}
</span>
{if $aRow.debt_cart_unpaid}
	<font color=brown size="1">({$oLanguage->getMessage('cart debt')}:
		{$oLanguage->PrintPrice($aRow.debt_cart_unpaid)})</font>
{/if}
{if $aRow.data!='prepay_customer' && $aRow.data!='debt_customer'}
	<table BORDER=0 CELLSPACING=0 CELLPADDING=0>
	<tr>
    <td style="border:0;">
		<div style="display:none;" id="div_edit_comment_{$aRow.id}">
			<input type=text id="input_edit_comment_{$aRow.id}" name="comment_{$aRow.id}" value="{$aRow.custom_id}">
		</div>
		<img style="float:right;" src="/image/design/comments.png" id="img_edit_comment_{$aRow.id}" alt="№ заказа" title="№ заказа">
		<img style="cursor:pointer;float:right;display:none;" src="/image/apply.png" id="img_save_comment_{$aRow.id}" alt="Сохранить" title="Сохранить"
	    	onclick="javascript:xajax_process_browse_url('?action=finance_customer_set_custom_id&amp;id={$aRow.id}&amp;cid='+encodeURIComponent($('#input_edit_comment_{$aRow.id}').val()));return false;">
    </td>
    </tr>
    </table>
{/if}
</td>
