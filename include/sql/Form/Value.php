<?
function SqlFormValueCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and fv.id='{$aData['id']}'";
	}

	$sSql="select fv.*
		from form_value AS fv
		where 1=1 
		".$sWhere."
		group by fv.id";

	return $sSql;
}
?>