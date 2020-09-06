<?
function SqlLogBalanceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and lb.id='{$aData['id']}'";
	}

	if ($aData['order']) {
		$sOrder.=" order by {$aData['order']}";
	}

	if ($aData['subtotal_year']!='') {
		$sWhere.=" and {$aData['subtotal_year']}";
	}
	if ($aData['subtotal_month']!='') {
		$sWhere.=" and {$aData['subtotal_month']}";
	}

	$sSql="select lb.*
			from log_balance as lb
			where 1=1
			".$sWhere
			.$sOrder;

	return $sSql;
}
?>