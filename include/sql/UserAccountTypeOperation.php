<?
function SqlUserAccountTypeOperationCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and uato.id='{$aData['id']}'";
	}

	if (isset($aData['is_provider_type_visible']))
	    $sWhere .= ' and is_provider_type_visible='.$aData['is_provider_type_visible'];

	$sSql="select uato.*
			from user_account_type_operation uato
			where 1=1 ".$sWhere;

	return $sSql;
}
?>