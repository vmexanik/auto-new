<?php
function SqlHbParamsEditorCall($aData) {
	Base::$aRequest['data']['table_']?$sTable=Base::$aRequest['data']['table_']:$sTable=Base::$aRequest['table'];
	
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and t.id='{$aData['id']}'";
	}

	$sSql="select t.* 
			from ".$sTable." as t
			where 1=1 ".$sWhere;

	return $sSql;
}
?>