<?
function SqlCustomerCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and u.id='".$aData['id']."'";
	}
	if ($aData['login']) {
		$sWhere.=" and u.login='".$aData['login']."'";
	}
	if ($aData['email']) {
		$sWhere.=" and u.email='".$aData['email']."'";
	}
	if ($aData['join']){
		$sJoin = " ".$aData['join'];
	}
	if ($aData['id_parent']) {
		$sWhere.=" and uc.id_parent='".$aData['id_parent']."'";
	}
	if (isset($aData['is_locked'])) {
		$sWhere.=" and uc.is_locked='".$aData['is_locked']."'";
	}
	if (isset($aData['is_test'])) {
		$sWhere.=" and u.is_test='".$aData['is_test']."'";
	}

	if ($aData['has_account_log']) {
		$sJoin.=" inner join user_account_log as ual on ual.id_user=u.id";
	}
	/* привязывать к тадлице менеджеров через left так как может быть удален менеджер
       и запись заказчика не показывается в mpanel, хотя она есть */
	$sSql="select ua.*, cg.*, uc.*, u.*, cg.visible as cg_visible
				, cg.name as customer_group_name
				, uum.login as manager_login, uum.email as manager_email, um.name as manager_name, um.phone as manager_phone
				,ua.amount as current_account_amount
				,uc.name as customer_name
				,concat(ifnull(uc.name,''),' ( ',u.login,' )',
					IF(uc.phone is null or uc.phone='','',concat(' ".Language::getMessage('tel.')." ',uc.phone))) name_customer
				".$sField."
		   from user u
				inner join user_customer uc on u.id=uc.id_user
				inner join user_account ua on u.id=ua.id_user
				inner join customer_group cg on cg.id=uc.id_customer_group
				left join user_manager um on uc.id_manager=um.id_user
				left join user uum on um.id_user=uum.id
				".$sJoin."
			where 1=1
				".$sWhere."
			group by u.id
				".$sOrder;

	return $sSql;
}
?>