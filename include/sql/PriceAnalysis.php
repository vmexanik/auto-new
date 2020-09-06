<?
function SqlPriceAnalysisCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pa.id='{$aData['id']}'";
	}

	$sSql="select pa.* "
		."\n from price_analysis as pa "
		."\n where 1=1 ".$sWhere
		;

	return $sSql;
}
?>