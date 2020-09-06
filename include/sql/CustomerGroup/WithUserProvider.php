<?
function SqlCustomerGroupWithUserProviderCall($aData) {
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cg.id='{$aData['id']}'";
	}

	$sSql="select cg. * , dp. * , up. *, cg.name as cg_name
		   from customer_group AS cg
			  inner join discount_provider AS dp ON cg.id = dp.id_customer_group
			  inner join user_provider AS up ON dp.id_user_provider = up.id_user
		   where 1=1
			  ".$sWhere."
		   order by up.name";

	return $sSql;
}
?>