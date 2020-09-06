<?
function SqlKeywordCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and k.id='{$aData['id']}'";
	}

	$sSql="select k.*
			from keyword as k
			where 1=1 ".$sWhere."
			group by k.id";

	return $sSql;
}
?>