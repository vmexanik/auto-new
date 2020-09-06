<?
function SqlCartHistoryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ch.id='{$aData['id']}'";
	}
	if ($aData['code']) {
		$sWhere.=" and ch.code='{$aData['code']}'";
	}
	if ($aData['make']) {
		$sWhere.=" and ch.make='{$aData['make']}'";
	}
	if ($aData['id_provider']) {
		$sWhere.=" and ch.id_provider='{$aData['id_provider']}'";
	}

	$sSql="select up.*, ch.*
		from cart_history as ch
		inner join user_provider as up on ch.id_provider = up.id_user
		where 1=1
		".$sWhere.$aData['order'].$aData['limit'];

	return $sSql;
}
?>