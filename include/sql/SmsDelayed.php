<?
function SqlSmsDelayedCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ls.id='{$aData['id']}'";
	}

	$sSql="select ls.*
		from sms_delayed as ls
		where 1=1
		".$sWhere.$aData['order'].$aData['limit'];

	return $sSql;
}
?>