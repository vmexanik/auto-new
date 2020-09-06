<?
function SqlProviderRegionWayAssocCall($aData) {

	$sWhere.=$aData['where'];

	$sSql="select prw.id, prw.name
			from provider_region_way as prw
			where visible=1
			".$sWhere;

	return $sSql;
}
?>