<?php
function SqlRoleActionCall($aData) {

	$sWhere.=$aData['where'];

	if (isset($aData['id_role_group'])) {
		$sWhere.=" and id_role_group='{$aData['id_role_group']}'";
	}
	
	/*if ($aData['id_role']) {
		$sJoin = "left join role_permissions rp on rp.id_action = ra.id";
		$sWhere.=" and rp.id_role='{$aData['id_role']}'";
	}*/
	
	$sOrder='';
	if ($aData['order'])
		$sOrder = ' order by '.$aData['order'];
	
	$sSql="select *
		   from role_action ra
			".$sJoin."
		   	where 1=1 "
		   	.$sWhere.$sOrder;
			
	return $sSql;
}
?>