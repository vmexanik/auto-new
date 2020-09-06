<?
function SqlUserGroupCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ug.id='{$aData['id']}'";
	}

	$sSql="select ug.*
			from user_group ug
			where 1=1
				".$sWhere;

	return $sSql;
}
?>