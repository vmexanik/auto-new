<?
function SqlLanguageCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and l.id='{$aData['id']}'";
	}

	$sSql="select l.*
			from language l
			where 1=1 ".$sWhere."
			group by l.id";

	return $sSql;
}
?>