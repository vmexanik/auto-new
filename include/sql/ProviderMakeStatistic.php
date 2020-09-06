<?
function SqlProviderMakeStatisticCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.=" and ps.id_user='{$aData['id_user']}'";
	}

	$sSql="select ps.make as id, ps.*
			from provider_statistic ps
			where 1=1
				".$sWhere;

	return $sSql;
}
?>