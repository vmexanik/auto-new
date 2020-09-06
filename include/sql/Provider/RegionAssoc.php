<?
function SqlProviderRegionAssocCall($aData) {

	$sWhere.=$aData['where'];

	$sSql="select pr.id, pr.name
			from provider_region as pr
			where visible=1
			".$sWhere;

	return $sSql;
}
?>