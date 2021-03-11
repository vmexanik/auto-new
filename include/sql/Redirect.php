<?
function SqlRedirectCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and r.id='".$aData['id']."'";
	}

	$sSql="select r.*
			from redirect as r
			where 1=1
				".$sWhere."
			";

	return $sSql;
}
?>