<?
function SqlReportCustomerAccountDebtCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}

	$sSql="select uc.*,u.*,ua.amount as current_account_amount
		from user as u

		inner join user_customer as uc on u.id=uc.id_user
		inner join user_account as ua on u.id=ua.id_user
		inner join user_account_log as ual on u.id=ual.id_user

		where 1=1 and u.is_test=0
		".$sWhere."
		group by u.id
		order by u.login
		";
	return $sSql;
}
?>
