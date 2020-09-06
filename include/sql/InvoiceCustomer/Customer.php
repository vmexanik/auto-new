<?
function SqlInvoiceCustomerCustomerCall($aData)
{
    if(Auth::$aUser['is_super_manager'])
        $sWhereManager = ' ';
    else
        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
    
	$sWhere.=$aData['where'];

	if ($aData['status_array']) {
		$sWhere.=" and c.order_status in (".implode(',',$aData['status_array']).")";
	}

	if (isset($aData['id_invoice_customer'])) {
		$sWhere.=" and c.id_invoice_customer='{$aData['id_invoice_customer']}'";
	}
	if ($aData['id_user']) {
		$sWhere.=" and c.id_user='{$aData['id_user']}'";
	}
	
	if ($aData['num_rating']) {
		$sWhere.=" and uc.num_rating='{$aData['num_rating']}'";
	}

	$sSql="select ua.*, cg.*, uc.*, u.*, cg.name as customer_group_name, uum.login as manager_login
			, count(c.id) as cart_count
			from cart as c
			inner join user as u on u.id=c.id_user
			inner join user_customer as uc on u.id=uc.id_user
			inner join user_account as ua on u.id=ua.id_user
			inner join customer_group as cg on cg.id=uc.id_customer_group
			inner join user_manager um on uc.id_manager=um.id_user
			inner join user uum on um.id_user=uum.id

			where 1=1
			".$sWhere.$sWhereManager."
			group by c.id_user";

	return $sSql;
}
?>