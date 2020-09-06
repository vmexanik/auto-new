<?
function SqlMarginPriceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and t.id='{$aData['id']}'";
	}

	$sSql="select * from (select mp.*, 
			if(pg.code is NULL,'-',pg.code) as code,
			if(c.title is NULL,'-',c.title) as brand, 
			if(up.name is NULL,'-',up.name) as provider, 
			if(pg.name is NULL,'-',pg.name) as price_group,
			if(cu.name is NULL,'-',cu.name) as currency
			from margin_price mp
			left join cat as c on c.id=mp.id_cat
			left join user_provider as up on up.id_user=mp.id_provider
			left join price_group as pg on pg.id=mp.id_price_group
			left join currency as cu on cu.id=mp.id_currency
			) as t where 1=1 
				".$sWhere;

	return $sSql;
}
?>