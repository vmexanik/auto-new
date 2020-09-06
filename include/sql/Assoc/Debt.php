<?
function SqlAssocDebtCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.="and ld.id_user='".$aData['id_user']."'";
	}

	$sSql="
		select ld.id_user,sum(amount)
		from log_debt as ld
		where ld.is_payed='0'
		".$sWhere."
		group by ld.id_user
		";

	return $sSql;
}
?>