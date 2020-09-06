<?
function SqlCartCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and c.id='{$aData['id']}'";
	}
	if ($aData['id_array']) {
		$sWhere.=" and c.id in (".implode(',',$aData['id_array']).")";
	}
	if ($aData['id_user']) {
		$sWhere.=" and c.id_user='{$aData['id_user']}'";
	}
	if ($aData['status_array']) {
		$sWhere.=" and c.order_status in (".implode(',',$aData['status_array']).")";
	}
	if (isset($aData['id_invoice_customer'])) {
		$sWhere.=" and c.id_invoice_customer='{$aData['id_invoice_customer']}'";
	}
	if ($aData['join_cart_log']) {
		$sJoin.=" inner join cart_log as cl on cl.id_cart=c.id";
	}

	$sSql="select u.*, uc.*, c.*
				, cg.name as customer_group_name, uum.login as manager_login
				, up.name as provider_name, prg.name as provider_region_name, prw.name as provider_region_way_name
				, concat(prg.code,' - ',prw.name) as provider_region_concat
			from cart as c
			".$sJoin."
			inner join user as u on u.id=c.id_user
			inner join user_customer uc on u.id=uc.id_user
			inner join user_account ua on u.id=ua.id_user
			inner join customer_group cg on cg.id=uc.id_customer_group
			inner join user_manager um on uc.id_manager=um.id_user
			inner join user uum on um.id_user=uum.id
			inner join user_provider as up on c.id_provider=up.id_user
			inner join provider_region as prg on up.id_provider_region=prg.id
			left join provider_region_way as prw on prg.id_provider_region_way=prw.id
			where 1=1
				".$sWhere."
			group by c.id
				".$aData['order'];

	return $sSql;
}
?>