<?
function SqlUserAccountLogTypeCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ualt.id='{$aData['id']}'";
	}

	$sSql="select ualt.*
			from user_account_log_type ualt
			where 1=1 ".$sWhere;

	return $sSql;
}
?>