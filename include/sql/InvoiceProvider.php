<?
function SqlInvoiceProviderCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ip.id='{$aData['id']}'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and ip.id_user='".$aData['id_user']."'";
	}
	if (isset($aData['is_sent'])) {
		$sWhere.=" and ip.is_sent='".$aData['is_sent']."'";
	}
	if (isset($aData['is_payed'])) {
		$sWhere.=" and ip.is_payed='".$aData['is_payed']."'";
	}

	$sSql="select u.*, up.*
				/*,cg.name as customer_group_name, uum.login as manager_login
				,a.name as account_name*/
				,ip.*
				,pr.name as provider_region_name
				,pr.code_delivery as provider_region_code_delivery
				,ua.amount as account_amount
				,pg.name as pg_name
				,c.name as name_currency
			from invoice_provider as ip
			inner join user as u on u.id=ip.id_user
			inner join user_provider as up on u.id=up.id_user
			inner join user_account as ua on u.id=ua.id_user
			inner join provider_region pr on up.id_provider_region=pr.id
			inner join provider_group as pg on up.id_provider_group=pg.id
			inner join currency c on c.id = up.id_currency
			where 1=1
			".$sWhere."
			group by ip.id
			";

	return $sSql;
}
?>