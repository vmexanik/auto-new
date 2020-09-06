<?
function SqlFinanceUserAccountLogTypeAssocCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['assoc_value']=='all') {
		$sField=" ,ualt.*";
	}
	else {
		$sField=" ,ualt.name";
	}

	$sSql="select ualt.id
			".$sField."
			from user_account_log_type as ualt
			where 1=1
				".$sWhere.
			" ".$aData['order'];

	return $sSql;
}
?>