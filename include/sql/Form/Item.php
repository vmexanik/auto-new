<?
function SqlFormItemCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and fi.id='{$aData['id']}'";
	}

	$sSql="select fi.*
		from form_item AS fi
		where 1=1 
		".$sWhere."
		group by fi.id";

	return $sSql;
}
?>