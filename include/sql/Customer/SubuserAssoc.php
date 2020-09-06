<?
function SqlCustomerSubuserAssocCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.=" and uc.id_parent='".$aData['id_user']."'";
	}

	$sSql="select uc.id_user, u.login
			from user_customer as uc
			inner join user as u on uc.id_user=u.id
			where 1=1 ".$sWhere;

	return $sSql;
}
?>