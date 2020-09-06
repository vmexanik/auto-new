<?
function SqlDropDownCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and d.id='{$aData['id']}'";
	}
	
	if ($aData['code']) {
		$sWhere.=" and d.code='{$aData['code']}'";
	}
	
	if ($aData['id_parent']) {
		$sWhere.=" and d.id_parent=".$aData['id_parent'];
	}
	
	$sSql="select d.*
			from drop_down as d
			where 1=1 ".$sWhere."
			group by d.id";

	return $sSql;
}
?>