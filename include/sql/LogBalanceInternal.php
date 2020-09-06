<?
function SqlLogBalanceInternalCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and lbi.id='{$aData['id']}'";
	}

	if ($aData['order']) {
		$sOrder.=" order by {$aData['order']}";
	}

	$sSql="select lbi.*
			from log_balance_internal as lbi
			where 1=1
			".$sWhere
			.$sOrder;

	return $sSql;
}
?>