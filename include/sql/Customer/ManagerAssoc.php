<?
function SqlCustomerManagerAssocCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user_array']) {
		$sWhere.=" and uc.id_user in (".implode(',',$aData['id_user_array']).")";
	}

	$sSql="select uc.id_user , m.login as manager_login
			from user_customer as uc
			inner join user as m on uc.id_manager=m.id
			where 1=1 ".$sWhere;

	return $sSql;
}
?>