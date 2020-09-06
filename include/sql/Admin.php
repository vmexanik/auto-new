<?
function SqlAdminCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and a.id='{$aData['id']}'";
	}

	$sSql="select * 
			from admin as a
			where 1=1 ".$sWhere."
			group by a.id";

	return $sSql;
}
?>