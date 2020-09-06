{if $smarty.request.status && $smarty.request.status=='store' && $aRow.id_user}
<tr>
	<td colspan=7 class="tbDet-footer" align=right>
		<a href="/?action=manager_invoice_customer_create&id_user={$aRow.id_user}"	target=_blank
		><img src="/image/inbox.png" border=0  width=16 align=absmiddle
		/>{$oLanguage->getMessage("Create Customer Invoice")}</a>
	</td>
</tr>
{/if}
<tr>
	<td colspan=7 class="tbDet-footer" align=right>
		<a target="_blank" class="show" 
href="./?search_login={$aUserCustomer.login}&search[customer_type]=-1&search[id_partner_region]=0&search_id_provider=0&search[date_type]=cart&search_date=1&search[date_from]={$smarty.now-120*86400|date_format:"%d.%m.%Y"}&search[date_to]={$smarty.now+86400|date_format:"%d.%m.%Y"}&search[change_period]=0{if $smarty.request.status}&search_order_status={$smarty.request.status}{else}&search_order_status=all_except_archive{/if}&search[order_status_type]=who&search[lock_order_status]=1&show_table=1&action=manager_order">

		{$oLanguage->getMessage("Open all")}
		</a>
	</td>
</tr>
