<?
function SqlGlossaryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and g.id='{$aData['id']}'";
	}

	$sSql="select * 
			from glossary as g
			where 1=1 ".$sWhere."
			group by g.id";

	return $sSql;
}
?>