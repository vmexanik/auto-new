<?
function SqlBillProviderCall($aData) {

    
    /*if(Auth::$aUser['is_super_manager'])
        $sWhereManager = ' ';
    else
        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";*/
    
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and b.id='{$aData['id']}'";
	}

	$sSql="select u.login
				/*, a.name as account_name*/
				, up.name
				, b.*, ual.id_bill
			from bill_provider as b
			inner join user as u on b.id_user=u.id
			inner join user_provider as up on u.id=up.id_user
			/*inner join account as a on b.id_account=a.id*/
			left join user_account_log ual on ual.id_bill = b.id
			where 1=1
				".$sWhere.$sWhereManager."
			group by b.id
				";

	return $sSql;
}
?>