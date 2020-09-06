<?
function SqlProviderVirtualCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pv.id='{$aData['id']}'";
	}

	$sSql="select pv.*, up.name as name, up1.name as name_virtual
			from provider_virtual as pv
			join user_provider as up on pv.id_provider=up.id_user
			join user_provider as up1 on pv.id_provider_virtual=up1.id_user
			where pv.id_provider<>pv.id_provider_virtual ".$sWhere
			;

	return $sSql;
}
?>