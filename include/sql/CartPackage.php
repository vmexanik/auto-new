<?
function SqlCartPackageCall($aData)
{
	$dTax=Base::GetConstant("price:tax", 19.6)/100;
	$sWhere.=$aData['where'];
	$sJoin.=$aData['join'];

	if ($aData['id'] && is_array($aData['id'])) {
		$sWhere.=" and cp.id in (".implode(",",$aData['id']).")";
	} elseif ($aData['id']) {
		$sWhere.=" and cp.id='".$aData['id']."'";
	}

	if ($aData['order_status'])
	{
		$sWhere.=" and cp.order_status='".$aData['order_status']."'";
	}

	if ($aData['id_user'])
	{
		$sWhere.=" and cp.id_user='".$aData['id_user']."'";
	}

	$sSql="select u.type_, u.login, u.email
			, uc.*
			, m.login as manager_login
			, cp.*, round((cp.price_total-cp.price_delivery)/(1+".$dTax."),2) as price_total_without_ttc
			, round(cp.price_total-cp.price_delivery-(cp.price_total-cp.price_delivery)/(1+".$dTax."),2) as price_ttc
			, round(cp.price_total-cp.price_delivery,2) as price_cart_ttc
			, round(".$dTax."*100,2) as tax
			, ".DateFormat::GetSqlDate("cp.post_date")." as date_bill
			, uc.zip, uc.address, uc.city, uc.phone, uc.phone2, uc.name
			, c.zip as user_contact_zip, c.address as user_contact_address, c.city as user_contact_city
			, c.phone as user_contact_phone, c.phone2 as user_contact_phone2, c.name as user_contact_name
			, concat(ifnull(concat(oc.name,' '),'')
				,ifnull(concat(c.street,' '),'')
				, ifnull(concat(c.house,' '),'')
				, ifnull(concat(c.apartment,' '),'')
				, ifnull(concat(c.office,' '),'')
			) as address_delivery
			,pt.name as payment_type_name
			,dt.name as delivery_type_name
			,pd.number_declaration as number_declaration
			,um.name as name_manager, unix_timestamp(now())-unix_timestamp(cp.post_date) as created
			,if (cp.post_date_changed!='0000-00-00 00:00:00',
				unix_timestamp(now())-unix_timestamp(cp.post_date_changed),
				cp.post_date_changed) as changed
			from cart_package cp
			left join payment_declaration as pd on cp.id_user=pd.id_user and pd.id_cart_package=cp.id
			inner join user as u on cp.id_user=u.id
			inner join user_customer as uc on u.id=uc.id_user
			inner join user m on uc.id_manager=m.id
			left join user_contact as c on cp.id_user_contact=c.id
			left join office_city as oc on c.id_city=oc.id
			left join payment_type as pt on cp.id_payment_type=pt.id
			left join delivery_type as dt on cp.id_delivery_type=dt.id
			left join user_manager um on um.id_user = cp.id_manager
				".$sJoin."
			where 1=1
				".$sWhere."
			group by cp.id";

	return $sSql;
}
?>