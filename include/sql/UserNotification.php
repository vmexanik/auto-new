<?
function SqlUserNotificationCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and un.id='{$aData['id']}'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and un.id_user='{$aData['id_user']}'";
	}
	if (isset($aData['is_sent'])) {
		$sWhere.=" and un.is_sent='{$aData['is_sent']}'";
	}
	if ($aData['order']) {
		$sOrder.=$aData['order'];
	}

	$sSql="select u.*, un.*
			from user_notification un
			inner join user as u on un.id_user=u.id
			where 1=1
			".$sWhere."
			".$sOrder;

	return $sSql;
}
?>