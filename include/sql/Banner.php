<?
function SqlBannerCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and b.id='{$aData['id']}'";
	}

	$sSql="select b.*
			from banner as b
			where 1=1
				".$sWhere."
			group by b.id
				".$aData['order'];

	return $sSql;
}
?>