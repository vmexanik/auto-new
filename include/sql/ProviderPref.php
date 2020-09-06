<?
function SqlProviderPrefCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pp.id='".$aData['id']."'";
	}

	$sSql="select pp.*, up.name as name
			from provider_pref as pp
			inner join user_provider as up on pp.id_user_provider=up.id_user
			where 1=1
			".$sWhere
			;

	return $sSql;
}
?>