<?php
function SqlRoleNameCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pa.id='{$aData['id']}'";
	}

	$sSql="select *
		   from role_name rn
		   where 1=1 "
		   .$sWhere;
			
	return $sSql;
}
?>