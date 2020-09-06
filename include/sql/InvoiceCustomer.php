<?
function SqlInvoiceCustomerCall($aData) {

    if(Auth::$aUser['is_super_manager'])
        $sWhereManager = ' ';
    else
        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
    
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ic.id='{$aData['id']}'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and ic.id_user='".$aData['id_user']."'";
	}
	if (isset($aData['is_sent'])) {
		$sWhere.=" and ic.is_sent='".$aData['is_sent']."'";
	}

	$sSql="select u.*, uc.*
				, cg.name as customer_group_name, uum.login as manager_login
				,a.name as account_name
				,ic.*
			from invoice_customer as ic
			inner join user as u on u.id=ic.id_user
			inner join user_customer as uc on u.id=uc.id_user
			inner join user_account as ua on u.id=ua.id_user
			inner join customer_group as cg on cg.id=uc.id_customer_group
			inner join user_manager as um on uc.id_manager=um.id_user
			inner join user as uum on um.id_user=uum.id
			inner join account as a on ic.id_account=a.id
			where 1=1
			".$sWhere.$sWhereManager."
			group by ic.id
			";

	return $sSql;
}
?>