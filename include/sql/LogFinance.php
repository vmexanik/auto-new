<?
function SqlLogFinanceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and t.id='{$aData['id']}'";
	}

	$sSql="select t.*,u.login as user
			from log_finance as t
			left join user as u on t.id_user=u.id
			where 1=1 ".$sWhere."
			";

	return $sSql;
}
?>