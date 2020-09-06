<?
function SqlStyleColoredCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and c.id='{$aData['id']}'";
	}

	$sSql="select c.*
			from style_colored as c
			where 1=1  ".$sWhere."
			";

	return $sSql;
}
?>