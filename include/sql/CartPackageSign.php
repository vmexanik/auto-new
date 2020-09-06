<?
function SqlCartPackageSignCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cps.id='{$aData['id']}'";
	}

	$sSql="select cps. *
			from cart_package_sign as cps
			where 1=1 ".$sWhere."
			group by cps.id";

	return $sSql;
}
?>