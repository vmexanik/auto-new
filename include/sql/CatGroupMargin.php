<?
function SqlCatGroupMarginCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cgm.id='".$aData['id']."'";
	}
	
	if ($aData['name']) {
		$sWhere.=" and cgm.name='".$aData['name']."'";
	}
	
	$sSql="select cgm.* "
	." from cat_group_margin as cgm"
	." where 1=1 "
	.$sWhere
	;

	return $sSql;
}
?>