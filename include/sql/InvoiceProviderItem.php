<?
function SqlInvoiceProviderItemCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ipi.id='{$aData['id']}'";
	}
	if ($aData['id_invoice_provider']) {
		$sWhere.=" and ipi.id_invoice_provider='{$aData['id_invoice_provider']}'";
	}

	$sSql="select ipi.*,c.cat_name,c.code,c.item_code,c.name_translate /*, pr.name as provider_region_name, prw.name as provdier_region_way_name
				, concat(pr.code,' - ',prw.name) as provider_region_concat*/
			from invoice_provider_item as ipi
			inner join cart as c on ipi.id_cart=c.id
			inner join user_provider as up on c.id_provider=up.id_user
			/*inner join provider_region as pr on up.id_provider_region=pr.id
			inner join provider_region_way as prw on pr.id_provider_region_way=prw.id*/
			where 1=1
			".$sWhere."
			group by ipi.id
			order by ipi.id_cart, ipi.post_date
			";

	return $sSql;
}
?>