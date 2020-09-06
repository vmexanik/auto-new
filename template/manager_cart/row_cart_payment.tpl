<td>{$aRow.id} </td>
<td>{$aRow.id_cart} {$aRow.cart_code}<br>
	{$oLanguage->AddOldParser('customer',$aRow.id_user)}</td>
<td>{$aRow.post_date}</td>
<td>{$aRow.number} </td>
<td>$ {$aRow.weight_payment} </td>
<td>$ {$aRow.volume_payment} </td>
<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_payed}
	{if $aRow.is_payed}{$aRow.post_date}{/if}
</td>
<td nowrap>
{if !$aRow.is_payed}
<a href="/?action=manager_cart_payment_pay&id={$aRow.id}&return={$sReturn|escape:"url"}"
	onClick="return (confirm('{$oLanguage->getMessage("Are you sure?")}'))"
	><img src="/image/tooloptions.png" border=0  width=16 align=absmiddle
	/>{$oLanguage->getMessage("Pay carts")}</a>


<br>
<a href="/?action=manager_cart_payment_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Invoice Edit")}</a>

<a href="/?action=manager_cart_payment_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
	onClick="return (confirm('{$oLanguage->getMessage("Are you sure?")}'))"
	><img src="/image/delete.png" border=0  width=16 align=absmiddle
	/>{$oLanguage->getMessage("Delete")}</a>

{/if}


</td>