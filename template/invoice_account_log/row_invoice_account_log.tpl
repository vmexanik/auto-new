{assign var='sReference' value=$aRow.section|cat:'_reference'}
{if $aRow.custom_id}
	{capture name=custom_text}{$oLanguage->GetMessage($sReference)} #{$aRow.custom_id}{/capture}
{else}
	{capture name=custom_text}{$oLanguage->GetMessage('Other invoice account log')}{/capture}
{/if}

{if !$aRow.hide_tr}
<td>
{if !$smarty.section.d.index}
	<font color=green>{$oLanguage->PrintPrice($aRow.invoice_account_amount)}</font>
{/if}

</td>
<td>{$oLanguage->PrintPrice($aRow.invoice_account_amount)}</td>
<td>{if $aRow.invoice_amount>=0}{$oLanguage->PrintPrice($aRow.invoice_amount)}{/if}</td>
<td>{if $aRow.invoice_amount<0}{$oLanguage->PrintPrice($aRow.invoice_amount)}{/if}
	{if $aRow.invoice_difference}<font color=red>{$aRow.invoice_difference}</font>{/if}
</td>
<td>{$aRow.post_date}</td>
<td>


<a class="control" id="show_sub_{$aRow.iTr}" href="javascript: mt.ToggleTr({$aItem[d].iTr});"
		> {$smarty.capture.custom_text}
 <img src="/image/icn_arrow_anchor.gif" /></a>

<a style="display: none;" class="control" id="hide_sub_{$aRow.iTr}" href="javascript: mt.ToggleTr({$aItem[d].iTr});"
		> {$smarty.capture.custom_text}
 <img src="/image/icn_back_top.gif" /></a>

</td>

{* ################################################################################################################# *}
{else}

<td colspan=5 class="doc_hd" >



{if $aRow.section=='user_account_log' && $aRow.custom_id}
	<b>{$smarty.capture.custom_text}</b>
	{$oLanguage->PrintPrice($aRow.amount)}

	{if $aRow.user_account_log_type_name}<b>{$aRow.user_account_log_type_name}</b>{/if}
	{$aRow.description}
{/if}


{if $aRow.section=='invoice_customer' && $aAuthUser.id_language==1}
<a id="show_detail_{$aRow.iTr}" href="javascript: general.ToggleTr('{$aRow.iTr}');"
		> <img src="/image/design/expandall.png" alt="" /></a>

<a style="display: none;" id="hide_detail_{$aRow.iTr}" href="javascript: general.ToggleTr('{$aRow.iTr}');"
		> <img src="/image/design/collapseall.png" alt="" /></a>

<b>{$smarty.capture.custom_text}</b>

{$aRow.post_date} {$oLanguage->PrintPrice($aRow.total)}

{/if}


</td>
<td>

{if $aRow.section=='invoice_customer'}
	<a href="./?action=invoice_account_log_invoice_print&id={$aRow.custom_id}" target=_blank
	><img src="/image/fileprint.png" border=0  width=16 align=absmiddle />{$oLanguage->getMessage("Print")}</a>

	{if $aAuthUser.id_language==1}
	<a href="./?action=invoice_account_log_get_invoice_excel&id={$aRow.custom_id}" target=_blank
	><img src="/image/disk_blue.png" border=0  width=16 align=absmiddle />{$oLanguage->getMessage("Download excel")}</a>
	{/if}
{/if}

</td>


	{if $aRow.section=='invoice_customer'}
<!-- Previous tr close, next open -->
</tr>
<tr style="display:none;" id='invoice_detail_{$aRow.iTr}'>
	<td colspan="5" class="doc_table" >

<table>
	{assign var='iIdCartPackagePrevious' value=''}
	{assign var='iIdCartPackageCurrent' value=''}

	{foreach from=$aRow.cart_item item=aCartItem}

	{assign var='iIdCartPackagePrevious' value=$iIdCartPackageCurrent}
	{assign var='iIdCartPackageCurrent' value=$aCartItem.id_cart_package}

	{if $iIdCartPackageCurrent!=$iIdCartPackagePrevious || $iIdCartPackagePrevious==''}
	<tr>
		<td colspan="2" class="doc_table" style="padding: 10px;">
			<font color=red><b>{$oLanguage->GetMessage('invoice cart package')} {$aCartItem.id_cart_package}</b></font>
		</td>
	</tr>
	{/if}



	<tr>
			<th><!--a href="javascript:;"><img src="/image/design/collapseall.png" alt="" /></a-->
				{$aCartItem.cat_name} {$aCartItem.code}
				{if $aRow.code_changed}<font color=red>{$aRow.code_changed}</font>{/if}
				- {$aCartItem.id} <font color=blue size=-3>{$aCartItem.provider_region_concat}</font>
				<font color=red size=-2>{$aCartItem.customer_id}</font>
				<font color=red size=-2>{$aCartItem.customer_comment}</font>

				<br><font size=-4>{$aCartItem.russian_name}</font> <font size=-4 color=gray>{$aCartItem.name}</font>
			</th>


			<th style="text-align:right">
				{if in_array($aCartItem.order_status,array('refused','store_refused'))}
					<strike><font color=red>
				{/if}

			{if $aCartItem.cart_price}

				{math equation="x/y" x=$aCartItem.cart_price y=$aCartItem.number assign=dSinglePrice}
				<font size=1>{$aCartItem.number} * {$oLanguage->PrintPrice($dSinglePrice)} = </font>

				{$oLanguage->PrintPrice($aCartItem.cart_price)}
			{else}
				{$oLanguage->GetMessage('Old data')}
			{/if}
			</th>
		</tr>
		{*if $aCartItem.invoice_customer_item}
		<tr>
			<td colspan="2" class="doc_table">
				<table>
				{foreach from=$aCartItem.invoice_customer_item item=aInvoiceCustomerItem}
				<tr>
					<td>{$aInvoiceCustomerItem.post_date}</td>
					<td>{$aInvoiceCustomerItem.item_description}</td>
					<td width=100px>{$oLanguage->PrintPrice($aInvoiceCustomerItem.item_amount)}</td>
				</tr>
				{/foreach}
				<tfoot>
				</tfoot>
				</table>
			</td>
		</tr>
		{/if*}
	{/foreach}


		<tr>
			<th>{$oLanguage->GetMessage('Итого с дополнительными платежами')}</th>
			<th style="text-align:right"></th>
		</tr>
		<tr>
			<td colspan="2" class="doc_table">
				<table>
				<tfoot>
				{foreach from=$aRow.additional_item item=aAdditionalItem}
				<tr>
					<td colspan="2">{$aAdditionalItem.post_date} - {$aAdditionalItem.description}</td>
					<td style="text-align:right">{$oLanguage->PrintPrice($aAdditionalItem.amount,false,true)}</td>
				</tr>
				{/foreach}
				<tr class="all_total">
					<td style="font-size: 13px;" colspan="2">{$oLanguage->GetMessage('Итого')}</td>
					<td style="font-size: 13px; text-align:right">{$oLanguage->PrintPrice($aRow.total)}</td>
				</tr>
				</tfoot>
				</table>
			</td>
		</tr>

		</table>


		</td>
	</tr>
	{/if}



{/if}