<?
function SqlAssocDebtCartCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.="and ld.id_user='".$aData['id_user']."'";
	}

	$sSql="
		select custom_id,sum(amount)
		from log_debt as ld
		where 1=1
		".$sWhere."
		group by custom_id
		";

	return $sSql;
}
?>