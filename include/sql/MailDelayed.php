<?
function SqlMailDelayedCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and md.id='{$aData['id']}'";
	}

	$sSql="select md.*,a.attach_file
		from mail_delayed as md
		left join attachment a on a.owner_code = md.attach_code
		where 1=1
		".$sWhere.$aData['order'].$aData['limit'].' group by md.id';

	return $sSql;
}
?>