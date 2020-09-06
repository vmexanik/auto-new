<?
function SqlCustomerBoxCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and b.id=".$aData['id'];
	}

	if ($aData['code']) {
		$sWhere .= " and b.code='" . addslashes ( $aData ['code'] )."'";
	}

	if ($aData['invoice']) {
		$sWhere .= " and b.invoice='" . addslashes ( $aData ['invoice'] )."'";
	}

	$sSql="select b.*
			from box b
			where 1=1
			".$sWhere;

	return $sSql;
}
?>