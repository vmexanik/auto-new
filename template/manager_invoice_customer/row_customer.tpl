<td>{if $aRow.id_language!=1 and $aRow.weight_delivery_cost>0}<span style="background-color:#FFFF70;">{$aRow.id}</span>{else}{$aRow.id}{/if}</td>
<td>
{assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
{$oLanguage->AddOldParser('customer_uniq',$Id)}
{if $aRow.is_office_client}
	<br><b>{$aRow.office_code}</b>
{/if}
<br>
<select onchange="
{strip}
xajax_process_browse_url('/?action=manager_invoice_customer_change_rating
&id_user={$aRow.id_user}&num_rating='+this.value); return false;
{/strip}"
			>
	{html_options options=$aRatingAssoc selected=$aRow.num_rating}
</select>
</td>
<td>{$aRow.cart_count}
{$oLanguage->AddOldParser('cart_store_end',$aRow.id_user)}

<br>
&nbsp;&nbsp;&nbsp;<a href="/?action=manager_order&search[uc_name]={$aRow.login}&search_order_status=store" target=_blank
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("View Store Carts")}</a>
{if $aRow.region_name}<br>&nbsp;&nbsp;&nbsp;{$aRow.region_name}{/if}
</td>
<td>{$aRow.cart_count_work}
{$oLanguage->AddOldParser('cart_work',$aRow.id_user)}

</td>

<td nowrap>
<a href="/?action=manager_invoice_customer_create&id_user={$aRow.id_user}"	target=_blank
	><img src="/image/inbox.png" border=0  width=16 align=absmiddle
	/>{$oLanguage->getMessage("Create Customer Invoice")}</a>
</td>
