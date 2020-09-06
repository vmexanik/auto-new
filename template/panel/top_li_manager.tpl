	<!--li class="icon-list"></li-->


<!--table width=95% cellapcing=5 cellpadding=0 border=0 align=center>
	<tr>
		<td colspan=2><div style="float:right;">{$aLeftCart.iCartPackage}</div>
		<a href='/?action=manager_package_list'>{$oLanguage->getMessage("CartPackages")}</a>
		</td>
	</tr>
	<tr>
		<td><a href='/?action=manager_order'>{$oLanguage->getMessage("Заказов в работе")}</a></td>
		<td align=right>{$aLeftCart.iOrder}</td>
	</tr>
	<tr>
		<td><a href='/?action=manager_vin_request'>{$oLanguage->getMessage("Vin requests")}</a></td>
		<td align=right>{if $aLeftCart.iVinRequestNew}<b><font size=4>{/if} {$aLeftCart.iVinRequest}</td>
	</tr>
	<tr>
		<td><a href='/?action=manager_customer'>{$oLanguage->getMessage("My customers")}</a></td>
		<td align=right>{$aLeftCart.iCustomer}</td>
	</tr>
	<tr>
		<td><a href='/?action=message'>{$oLanguage->getMessage("Сообщения")}</a></td>
		<td align=right>{$aLeftCart.iMessage}</td>
	</tr>
	<tr>
		<td><a href="/?action=manager_bill'>{$oLanguage->getMessage("Счета на оплату")}</a></td>
		<td align=right>{$aLeftCart.iBill}</td>
	</tr>
</table-->