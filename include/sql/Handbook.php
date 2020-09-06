<?php
function SqlHandbookCall($aData) {
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and h.id='{$aData['id']}'";
	}

	$sSql="select * 
			from handbook h
			where 1=1 ".$sWhere;

	return $sSql;
}
?>