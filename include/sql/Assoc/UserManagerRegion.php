<?
function SqlAssocUserManagerRegionCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.="and umr.id_user='".$aData['id_user']."'";
	}

	$sSql="
		select umr.id_provider_region, umr.id_user
		from user_manager_region as umr
		where 1=1
		".$sWhere;

	return $sSql;
}
?>