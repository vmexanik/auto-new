<?
function SqlAssocDistinctInvoiceCall($aData)
{
	$sWhere.=$aData['where'];

	$sSql="
		select concat('\'',c.id_provider_invoice,'\''),c.id
		from cart as c
		inner join cart_log as cl on cl.id_cart=c.id
		where 1=1 and c.id_provider_invoice!=''
		".$sWhere."
		group by c.id_provider_invoice
		";

	return $sSql;
}
?>