<?
function SqlAccessoryImageCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ai.id='{$aData['id']}'";
	}
	if (isset($aData['visible'])) {
		$sWhere.=" and ai.visible='{$aData['visible']}'";
	}

	$sSql="select ai. *
			from accessory_image as ai
			where 1=1 ".$sWhere."
			group by ai.id";

	return $sSql;
}
?>