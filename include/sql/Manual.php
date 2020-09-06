<?
function SqlManualCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and m.id='".$aData['id']."'";
	}
	if (isset($aData['visible'])) {
		$sWhere.=" and m.visible='".$aData['visible']."'";
	}
	if ($aData['user_type']) {
		$sWhere.=" and m.user_type='".$aData['user_type']."'";
	}

	$sSql = "select m.*,mc.name as manual_category_name,mc.code as manual_code 
			from manual as m 
			inner join manual_category as mc on m.code_manual_category=mc.code 
			where 1=1
			".$sWhere;
	
	return $sSql;
}
?>