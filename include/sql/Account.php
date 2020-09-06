<?
function SqlAccountCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and a.id='".$aData['id']."'";
	}
	if ($aData['is_active']) {
		$sWhere.=" and a.is_active='1'";
	}

	if ($aData['in_use_bv']) {
		$sWhere.=" and a.in_use_bv='1'";
	}
	
	if ($aData['in_use_rko']) {
		$sWhere.=" and a.in_use_rko='1'";
	}
	
	if ($aData['in_use_pko']) {
		$sWhere.=" and a.in_use_pko='1'";
	}
	
	if ($aData['visible']) {
		$sWhere.=" and a.visible='1'";
	}
	
	
	$sSql="select a.*,c.code as currency_code
			from account as a
			inner join currency as c ON c.id = a.id_currency
			where 1=1
				".$sWhere;

	return $sSql;
}
?>