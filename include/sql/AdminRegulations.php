<?
function SqlAdminRegulationsCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ar.code='".$aData['code']."'";
	}

	$sSql="select ar.*
			from admin_regulations as ar
			where 1=1
				".$sWhere;

	return $sSql;
}
?>