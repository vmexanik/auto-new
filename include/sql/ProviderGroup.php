<?
function SqlProviderGroupCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pg.id='{$aData['id']}'";
	}

	$sSql="select pg.*
			from provider_group pg
			where 1=1
				".$sWhere;

	return $sSql;
}
?>