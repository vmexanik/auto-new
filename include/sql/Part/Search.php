<?
function SqlPartSearchCall($aData) {

    if(Auth::$aUser['is_super_manager'])
        $sWhereManager = ' ';
    else if(Base::$aRequest['action']=='manager_order')
        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
    
	$sWhere=$aData['where'];
	$dTax=Base::GetConstant("price:tax", 19.6)/100;

	Db::SetWhere($sWhere,$aData,'id','c');
	Db::SetWhere($sWhere,$aData,'id_cart','c','id');
	//Db::SetWhere($sWhere,$aData,'id_cart_package','c');
	if ($aData['id_provider_ordered']) {
		$sWhere.=" and c.id_provider_ordered=".$aData['id_provider_ordered'];
	} else {
		Db::SetWhere($sWhere,$aData,'id_provider','c');
	}

	Db::SetWhere($sWhere,$aData,'number','c');
	Db::SetWhere($sWhere,$aData,'pref','c');
	Db::SetWhere($sWhere,$aData,'login','u');
	Db::SetWhere($sWhere,$aData,'id_provider_region_way','pr');
	Db::SetWhere($sWhere,$aData,'id_cart_store','csc');


	if ($aData['id_cart_package']) {
		$sWhere.=" and id_cart_package in (".$aData['id_cart_package'].")";
	}

	if ($aData["cart_notconfirm"])
	{
		$sWhere.=" and ifnull(csc.id,'')=''";
	}

	if ($aData["id_user_manager"])
	{
		$aData['cart_log_join']=1;
		if ($aData['order_status_type']=='whose') {
			$sTableField='uc.id_manager';
		} else {
			$sTableField='cl.id_user_manager';
		}
		$sWhere.=" and ".$sTableField."='".$aData["id_user_manager"]."'";
	}

	if ($aData['code'])
	{
		if ($aData['inReplacement'])
		{
			$sWhere.=" and c.code in ('".$aData['code']."',".$aData['inReplacement'].")";
		}
		else
		{
			$sWhere.=" and (c.code like '%".$aData['code']."%' or c.code_changed  like '%".$aData['code']."%') ";
		}
	}

	if ($aData['name']) {
		$sWhere.=" and (ifnull(cpt.name,c.name) like '%".$aData['name']."%'
		or ifnull(cpt.name_rus,c.name_translate) like '%".$aData['name']."%'
		)
		";
	}

	if ($aData['uc_name']) {
		$sWhere.=" and (u.login='".$aData['uc_name']."'
		or uc.name like '".$aData['uc_name']."%'
		)
		";
	}

	if ($aData['cart_log_join']) {
		$sJoin.=" left join cart_log as cl on (cl.id_cart=c.id and cl.order_status=c.order_status) ";
	}

	if ($aData['type_']!='cart') {
		$sWhere.=" and c.type_='order'";
		//		if ($aData['is_confirm']) {
		//			$sWhere.=" and cp.is_confirm=1";
		//		}
		$sJoin.=" inner join cart_package as cp on cp.id=c.id_cart_package
			 	left join delivery_type dt on dt.id = cp.id_delivery_type";
		$sField.=" , cp.* , ".Db::GetDateFormat('cp.post_date',"%d.%m %H:%i")." as cp_post_date_f
				, (c.term - datediff(now(), cp.post_date)) as term_last
				, dt.name as delivery_type_name,
				(unix_timestamp(now())-unix_timestamp(cp.post_date)) as created";
		if ($aData['cp_date_from']) {
			$sWhere.=" and cp.post_date>=".Db::GetStrToDate($aData['cp_date_from']);
		}

		if ($aData['cp_date_to']) {
			$sWhere.=" and cp.post_date<=".Db::GetStrToDate($aData['cp_date_to'])." +interval 1 day - interval 1 second";
		}

	} else {
		$sWhere.=" and c.type_='cart'";
	}

	if ($aData['date_from']) {
		$sWhere.=" and c.post_date>=".Db::GetStrToDate($aData['date_from']);
	}

	if ($aData['date_to']) {
		$sWhere.=" and c.post_date<=".Db::GetStrToDate($aData['date_to'])." +interval 1 day - interval 1 second";
	}

	if ($aData['is_buh_balance']) {
		$sField.=", ifnull(bem.amount_credit_end-bem.amount_debit_end,0) as buh_balance";
		$sCurrentPeriod=Base::GetConstant("buh:current_period");
		$sJoin.=" left join buh_entry_month as bem on bem.date_month='".$sCurrentPeriod."'
			and bem.id_buh=361 and u.id=bem.id_buh_subconto1 ";
	}

	$sSql="select cg.*,ua.*, u.*,uc.*, uc.name as customer_name, cat.title as brand
				".$sField."
				, c.*, c.price/(1+".$dTax.") as price_without_ttc
				, c.price_original*c.number as total_original, c.price_original
				, c.price*c.number-c.price_original*c.number as total_profit
				, c.price*c.number as total
				, m.login as manager_login
				, um.name as manager_name
				, ifnull(cpt.name,c.name) as name
				, if (cpt.name is NULL || cpt.name='',c.name_translate,cpt.name) as name_translate
				, ifnull(round(cpt.weight,2),0) as weight, cpt.unit_name
				, uc.name as customer_name, uc.manager_comment as customer_manager_comment
				, cat_changed.title as cat_name_changed
				, if(not c.pref_changed is null and c.pref<>c.pref_changed, cat_changed.title, '') as new_brand
				, up.id_currency as id_currency_provider
				, up.name as provider_name
				, cpt.hide_tof_image
				, ua_p.amount as provider_account_amount
			 from cart c
			 inner join cat on cat.pref = c.pref
			 inner join user as u on c.id_user=u.id
			 left join user_customer as  uc on uc.id_user=u.id
			 left join customer_group as  cg on uc.id_customer_group=cg.id
			 left join user as m on uc.id_manager=m.id
			 left join user_account as ua on ua.id_user=u.id
             left join user_manager as um on um.id_user=m.id
			 inner join user_provider as up on up.id_user=c.id_provider
			 left join user_account as ua_p on ua_p.id_user=c.id_provider

			 left join cat_part as cpt on c.item_code=cpt.item_code
			 left join user_customer as ucp on uc.id_parent=ucp.id_user
			 left join cat as cat_changed on cat_changed.pref=c.pref_changed
			".$sJoin."
			where 1=1
			".$sWhere.$sWhereManager."
			group by c.id ";

	return $sSql;


	//	, pr.name as pr_name, prw.name as prw_name, prw.code as prw_code
	//
	//	inner join provider_region as pr on pr.id=up.id_provider_region
	//	inner join provider_region_way as  prw on prw.id=pr.id_provider_region_way

}
?>