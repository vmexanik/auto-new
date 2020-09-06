<?
function SqlAssocCartInWorkCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.="and c.id_user='".$aData['id_user']."'";
	}

	$sSql="
		select c.id_user, sum(c.price * c.number - c.full_payment_discount)
		from cart as c
		where c.order_status in ('new','work','confirmed','road','store','end')
			and c.id_invoice_customer='0'
		".$sWhere."
		group by c.id_user
		";

	return $sSql;
}
?>