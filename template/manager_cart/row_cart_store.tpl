<td>{$oLanguage->AddOldParser('customer',$aRow.id_user)}

{$oLanguage->AddOldParser('comment_customer_popup',$aRow.id_user)}
</td>

<td>{$oCurrency->PrintPrice($aRow.amount)}

</td>
<td>{$aRow.cart_number}</td>
<td>{$aRow.region_name}</td>
<td align=right nowrap>

<a href="/?action=manager_order&search_login={$aRow.login}&search_order_status=store" target=_blank
	><img src="/image/comment.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("View Store Carts")}</a>
<br>
<a href="/?action=manager_finance&search_login={$aRow.login}" target=_blank
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("View Account Log")}</a>

</td>
