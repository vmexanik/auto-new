<td>{$aRow.id}</td>
<td>{$aRow.item_code}</td>
<td><b><font color=red>$&nbsp; {math equation="a * (b-c)" a=$aRow.number b=$aRow.price c=$aRow.provider_price}</font></b>
	({$aRow.number} * ({$aRow.price} - {$aRow.provider_price}))
	</td>
<td>{$aRow.login_vin_request}</td>
<td>{$aRow.customer_login}</td>
<td>{$aRow.cart_log_post_date}</td>
<td>{$aRow.order_status}</td>
