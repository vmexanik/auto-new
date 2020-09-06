<?
function SqlPopularProductsCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and p.id='{$aData['id']}'";
	}

	$sSql="select p.*
			from popular_products as p
			where 1=1
				".$sWhere."
			group by p.id
				".$aData['order'];

	return $sSql;
}
?>