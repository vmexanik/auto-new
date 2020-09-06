<?
function SqlDirectoryTagCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and dt.id='{$aData['id']}'";
	}

	$sSql="select dt.*
			from directory_tag dt
			where 1=1
				".$sWhere;

	return $sSql;
}
?>