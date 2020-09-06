<?
function SqlFaqCategoryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and fc.id='{$aData['id']}'";
	}

	$sSql="select * 
			from faq_category as fc
			where 1=1 ".$sWhere."
			order by fc.num";

	return $sSql;
}
?>