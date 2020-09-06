<?
function SqlArticleCategoryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ac.id='{$aData['id']}'";
	}

	$sSql="select * 
			from article_category as ac
			where 1=1 ".$sWhere."
			order by ac.num";

	return $sSql;
}
?>