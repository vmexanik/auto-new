<?
function SqlVinRequestMyQueueCall($aData) {

	if ($aData['view_all']=='1') {
	}
	else {
		$sWhere.=" and ( vr.id_manager_fixed in ('{$aData['id_manager']}','0')
			or vr.refuse_for='".Auth::$aUser['login']."')";
	}

	if ($aData['assoc']=='1') {
		$sField.=", vr.id as value";
	}

	$sSql="SELECT vr.id
			".$sField."
		from vin_request vr
		where 1=1 ".$sWhere;

	return $sSql;
}
?>
