<?
function SqlReportCartBalanceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}

	$sSql="select ua.*, u.* ,ua.amount as current_account_amount
				, ual.account_amount, ual.debt_amount
				".$sField."
		   from user u
				inner join user_customer uc on u.id=uc.id_user
				inner join user_account ua on u.id=ua.id_user
				inner join customer_group cg on cg.id=uc.id_customer_group
				inner join user_account_log as ual on (ual.id_user=u.id and ual.post_date>='".$aData['date_from']."')
				".$sJoin."
			where 1=1
				".$sWhere."
			group by u.id
			order by ual.post_date desc
				";
	return $sSql;
}
?>
