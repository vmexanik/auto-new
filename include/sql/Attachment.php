<?
function SqlAttachmentCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and at.id='{$aData['id']}'";
	}
	if ($aData['owner_code']) {
		$sWhere.=" and at.owner_code='{$aData['owner_code']}'";
	}

	$sSql="select at.*
		from attachment as at
		where 1=1 ".$sWhere;

	return $sSql;
}
?>
