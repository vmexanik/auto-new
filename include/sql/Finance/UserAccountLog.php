<?
function SqlFinanceUserAccountLogCall($aData) {

	$sWhere.=$aData['where'];

	$sSql = "select ualt.*, ual.*,u.login , ua.amount as current_account_amount
				, ualt.name as user_account_log_type_name
			from user_account_log ual
			inner join user as u on ual.id_user=u.id
			inner join user_account as ua on ua.id_user=u.id
			inner join user_account_log_type as ualt on ual.id_user_account_log_type=ualt.id
			where 1=1
			".$sWhere;

	return $sSql;
}
?>