<?
function SqlCustomerGroupCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cg.id='{$aData['id']}'";
	}

	$sSql="select cg.*, /* dp.*, up.*, */ cg.name as cg_name
			from customer_group as cg
			/*inner join discount_provider as dp on cg.id = dp.id_customer_group
			inner join user_provider as up on dp.id_user_provider = up.id_user*/
			where 1=1 and cg.visible=1 
			".$sWhere."
			group by cg.id";

	return $sSql;
}
?>