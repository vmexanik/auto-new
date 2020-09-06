<?php

function SqlLogAdminCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and la.id='".$aData['id']."'";
	}

	$sSql="select la.*
			from log_admin as la
			where 1=1
			".$sWhere;

	return $sSql;
}

?>
