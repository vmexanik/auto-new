<?php
function SqlRoleActionExeptionCall($aData) {

	$sWhere.=$aData['where'];
	
	if ($aData['id']) {
		$sWhere.=" and re.id='{$aData['id']}'";
	}
	$sSql="select *
		   from role_action_exeption re
		   where 1=1 "
		   .$sWhere;
			
	return $sSql;
}
?>