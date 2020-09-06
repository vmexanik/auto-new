<?
function SqlCatInfoImportCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,"id","cii");

	if ($aData['order']) {
		$sOrder.=" order by ".$aData['order'];
	}


	$sSql="select cii.*
		from cat_info_import as cii
		".$sJoin."
		where 1=1
		".$sWhere
		.$sOrder;

	return $sSql;
}
?>