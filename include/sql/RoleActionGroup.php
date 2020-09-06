<?php
function SqlRoleActionGroupCall($aData) {

	$sWhere.=$aData['where'];

	$sSql="select *
		   from role_action_group rag
		   where 1=1 "
		   .$sWhere;
			
	return $sSql;
}
?>