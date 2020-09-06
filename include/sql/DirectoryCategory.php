<?
function SqlDirectoryCategoryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and dc.id='{$aData['id']}'";
	}

	$sSql="select * 
			from directory_category as dc
			where 1=1 ".$sWhere."
			order by dc.num";

	return $sSql;
}
?>