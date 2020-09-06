<?
function SqlUserAccountLogCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ual.id='".$aData['id']."'";
	}
	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}

	if ($aData['order']) {
		$sOrder.=" '{$aData['order']}'";
	}

	if ($aData['sum']) {
		$sSelectedRow = " sum(".$aData['sum'].") as total ";
		$sWhere.=" and (cp.id is not null || ual.data='package_return' || ual.data='debt_customer' || ual.data='prepay_customer')";
	}else {
		$sSelectedRow = " uc.id_user_referer, u.login, ual.*, u.login as user, ua.amount as current_account_amount
			, ualt.name as user_account_log_type_name, ual.id as ual_id";
		$sJoin = "left join user_account_log_type as ualt on ual.id_user_account_log_type_debit=ualt.id  ";
	}

	if ($aData['join_additional']) {
		$sJoin.="inner join invoice_customer_additional as ica on (ica.id_user_account_log=ual.id
			and ica.id_invoice_customer='".$aData['id_invoice_customer']."')";
	}

	if ($aData['join_account']) {
		$sJoin.="left join account as a on ual.id_subconto1=a.id";
		$sField.=" , a.title as account_title, a.name as account_name";
	}
	if ($aData['join_calculator_item']) {
		$sJoin.="left join calculator_item as ci on ual.custom_id=ci.id and ual.section = '' ";
		$sField.=" , if(ci.invoice_name or (ual.custom_id=0),'1','0') as for_invoice ";
	}
    if ($aData['id_subconto1']) {
		$sWhere.=" and ual.id_subconto1 = '".$aData['id_subconto1']."'";
	}
    if ($aData['amount_currency']) {
		$sField.=",SUBSTRING_INDEX(SUBSTRING_INDEX(ual.`data`,' ',-2),' ',1) as amount_currency";
	}

	$query = "select ".$sSelectedRow.$sField.",u.type_,o.name as office_name, cp.id as id_cart_package, 
			(ual.account_amount-ual.amount) as debt_amount, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".Language::getMessage('tel.')." ',uc.phone))) name_customer,
				c.code, c.name_translate, ifnull(up.name,concat('( ',u.login,' )')) name_provider, upg.id_group
			from user_account_log ual
			left join user as u on ual.id_user=u.id
			left join user_account as ua on ua.id_user=u.id
			left join user_customer as uc on uc.id_user=u.id
			left join user_provider as up on up.id_user=u.id
			left join office as o on o.id = ual.id_office
			left join cart_package cp on cp.id = ual.custom_id
			left join cart c on c.id = ual.id_cart
			left join user_provider_group upg on upg.id_user = ual.id_user
			".$sJoin;

	if ($aData['join1']) {
		$sqlPart1 = $aData['join1'];
	}

	if ($aData['join2']) {
		$sqlPart2 =" union ".$query." ".$aData['join2']." where 1=1 ".$sWhere;
	}

	$sSql = $query." ".$sqlPart1." where 1=1 and u.visible=1 ".$sWhere.$sqlPart2." ".$sOrder;

	return $sSql;
}
?>