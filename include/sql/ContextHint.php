<?
function SqlContextHintCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ch.id='{$aData['id']}'";
	}

	$sSql="select ch.*
			from context_hint as ch
			where 1=1 ".$sWhere."
			group by ch.id";

	return $sSql;
}
?>