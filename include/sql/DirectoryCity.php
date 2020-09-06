<?
function SqlDirectoryCityCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and dc.id='{$aData['id']}'";
	}

	$sSql="select dc.*
			from directory_city dc
			where 1=1
				".$sWhere;

	return $sSql;
}
?>