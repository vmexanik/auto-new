<?
function SqlUserBalanceCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ua.id='".$aData['id']."'";
	}
	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}

	if ($aData['order']) {
		$sOrder.=$aData['order'];
	}

	$query = "select amount
			from user_account ua
			left join user as u on ua.id_user=u.id
			".$sJoin;

	$sSql = $query." where 1=1 ".$sWhere." ".$sOrder;

	return $sSql;
}
?>