<?
function SqlMessageNoteCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and mn.id='{$aData['id']}'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and mn.id_user='{$aData['id_user']}'";
	}
	if (is_numeric($aData['is_closed'])) {
		$sWhere.=" and mn.is_closed='{$aData['is_closed']}'";
	}

	$sSql="select u.*, mn.*
			from message_note mn
			inner join user as u on mn.id_user=u.id
			where 1=1
				".$sWhere."
			group by mn.id";

	return $sSql;
}
?>