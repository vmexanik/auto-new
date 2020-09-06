<?
function SqlCustomerGroupCustomerGroupCall($aData) {
	$sWhere.=$aData['where'];
	
	if ($aData['id']) {
		$sWhere.=" and cg.id='{$aData['id']}'";
	}
	
	$sSql="select cg. * ,cg.name as cg_name
			from customer_group AS cg
			".$sJoin."
			where 1=1 
			".$sWhere." 
			group by cg.id";
			
	return $sSql;
}
?>