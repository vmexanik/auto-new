<?
function SqlCustomerDebtCall($aData) {

	$sWhere.=$aData['where'];

	//	if ($aData['id']) {
	//		$sWhere.=" and u.id='{$aData['id']}'";
	//	}

	$sSql="select ld.id_user as id_user, sum(ld.amount) as amount
			from log_debt ld
			where 1=1 and is_payed='0'
				".$sWhere."
			group by ld.id_user";

	return $sSql;
}
?>