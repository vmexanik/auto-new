<?
function SqlProviderCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and u.id='{$aData['id']}'";
	}
	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}

	$sSql="select u.*, up.*
				,pr.name as provider_region_name
				,pr.code_delivery as provider_region_code_delivery
				,ua.amount as account_amount
				,pg.name as pg_name
				,c.name as name_currency
				,u.type_ as user_type
				,upg.id_group, m.amount as provider_group_amount
			from user u
			inner join user_provider up on u.id=up.id_user
			inner join provider_region pr on up.id_provider_region=pr.id
			inner join user_account as ua on u.id=ua.id_user
			inner join provider_group as pg on up.id_provider_group=pg.id
			inner join currency c on c.id = up.id_currency
			left join user_provider_group upg on upg.id_user = u.id
			left join user_provider_group_main m on m.id = upg.id_group
			where 1=1
				 ".$sWhere;//."
			//group by u.id";

	return $sSql;
}
?>