<?
function SqlDiscountCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and d.id='{$aData['id']}'";
	}

	$sSql="select d.*
			from discount d
			where 1=1
				".$sWhere;

	return $sSql;
}
?>