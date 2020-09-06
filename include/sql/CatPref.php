<?
function SqlCatPrefCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cp.id='{$aData['id']}'";
	}
	
	if ($aData['pref']) {
		$sWhere.=" and c.pref='{$aData['pref']}'";
	}
	
	if ($aData['name']) {
		$sWhere.=" and (cp.name='".mb_strtoupper($aData['name'],'utf-8')."' || cp.name='".mb_strtolower($aData['name'],'utf-8')."')";
	}
	
	$sJoin = "inner join cat c on c.id=cp.cat_id";
	if ($aData['is_left'])
		$sJoin = "left join cat c on c.id=cp.cat_id";
	
	$sSql="select cp.*,c.pref "
	." from cat_pref as cp ".$sJoin 
	." where 1=1 "
	.$sWhere
	;

	return $sSql;
}
?>