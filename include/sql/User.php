<?
function SqlUserCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and u.id='".$aData['id']."'";
	}

	if ($aData['visible']) {
		$sWhere.=" and u.visible=1";
	}

	if ($aData['login']) {
		if ($aData['or_email']) $sWhere.=" and '".$aData['login']."' in (u.login, u.email)";
		else $sWhere.=" and u.login='".$aData['login']."'";
	}

    if ($aData['type_']) {
        $sWhere.=" and u.type_='".$aData['type_']."'";
    }

	$sSql="select u.*
		from user as u
		where 1=1 ".$sWhere;
	return $sSql;
}
?>