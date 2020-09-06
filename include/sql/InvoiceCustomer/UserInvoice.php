<?
function SqlInvoiceCustomerUserInvoiceCall($aData)
{
	$sWhere.=$aData['where'];

	if (isset($aData['id_invoice_customer'])) {
		$sWhere.=" and c.id_invoice_customer='".$aData['id_invoice_customer']."'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and c.id_user='".$aData['id_user']."'";
	}

	if ($aData['join_delivery_cost']) {
		$sJoin.=" left join delivery_cost_link as dcl on dcl.id_cart = c.id
		left join delivery_cost as dc on dc.id=dcl.id_delivery_cost ";
		$sField.=" , dc.weight delivery_cost_weight, dc.cost delivery_cost_cost, dc.total delivery_cost_total
		,dc.delivery_customer_cost,dc.delivery_customer_tax,dc.tarif_tax
		,dcl.id delivery_cost_link_id, dc.id delivery_cost_id, dc.delivery_tax delivery_cost_tax
		 ";
	}

	$sSql="select c.*
			,(c.number*c.price-c.full_payment_discount) as total_price
			,c.price as single_price
			, prg.name as provider_region_name
			, prg.code as provider_region_concat
			, u.login
			,ic.post_date as invoice_post_date
			, cpt.name_rus,c.name_translate
			, ifnull(cpt.name_rus,c.name_translate) as russian_name
			, cpt.unit_name
			, cp.post_date cart_package_post_date
			, cat_changed.title as cat_name_changed
	        , cat.title as cat_title 
			".$sField."
			from cart as c
			inner join user_provider as up on c.id_provider=up.id_user
			inner join provider_region as prg on up.id_provider_region=prg.id
			inner join cat on cat.pref=c.pref

			inner join user as u on c.id_user=u.id
			inner join invoice_customer as ic on c.id_invoice_customer=ic.id
			left join cart_package as cp on cp.id = c.id_cart_package
			left join cat_part as cpt on c.item_code=cpt.item_code
			left join cat as cat_changed on cat_changed.pref=c.pref_changed
			".$sJoin."
			where 1=1
			".$sWhere."
			order by c.id_invoice_customer, c.id_cart_package, c.id, prg.name";

	return $sSql;
}
?>