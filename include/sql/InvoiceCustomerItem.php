<?
function SqlInvoiceCustomerItemCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ici.id='{$aData['id']}'";
	}

	$sSql="select ici.*, pr.name as provider_region_name, prw.name as provdier_region_way_name
				, concat(pr.code,' - ',prw.name) as provider_region_concat
			from invoice_customer_item as ici
			inner join cart as c on ici.id_cart=c.id
			inner join user_provider as up on c.id_provider=up.id_user
			inner join provider_region as pr on up.id_provider_region=pr.id
			inner join provider_region_way as prw on pr.id_provider_region_way=prw.id
			where 1=1
			".$sWhere."
			group by ici.id
			order by pr.name, ici.id_cart, ici.post_date
			";

	return $sSql;
}
?>