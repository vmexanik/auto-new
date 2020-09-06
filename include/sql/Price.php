<?
function SqlPriceCall($aData)
{
    $aData['where']=str_replace("AND pgs.id_price_group LIKE '%0%'", "and pgs.id_price_group is null", $aData['where']);
	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere,$aData,'id','price');
	Db::SetWhere($sWhere,$aData,'id_provider','price');
	Db::SetWhere($sWhere,$aData,'code','price');
	Db::SetWhere($sWhere,$aData,'price','price');
	Db::SetWhere($sWhere,$aData,'part_rus','price');
	Db::SetWhere($sWhere,$aData,'pref','price');
	Db::SetWhere($sWhere,$aData,'cat','price');
	
	if ($aData['join']) {
		$sJoin .= " ".$aData['join'];
	}

	if ($aData['order']) {
		$sOrder.=" order by ".$aData['order'];
	}
	
	if(isset($aData['id_price_group'])) {
	    if($aData['id_price_group']==0) $sWhere.=" and pgs.id_price_group is null ";
	    else $sWhere.=" and pgs.id_price_group='".$aData['id_price_group']."' ";
	}

	$sSql="select price.*, pgs.id_price_group, pg.name as group_name, up.name as provider_name, 
			u.login as provider_login, pg.name, u.visible as provider_visible, prg.name as prg_name,c.visible as cat_visible
			from price as price
			left join price_group_assign as pgs on pgs.item_code=price.item_code
	        left join user_provider as up on up.id_user=price.id_provider
	        left join provider_group as prg on up.id_provider_group=prg.id
	        left join user as u on u.id=up.id_user
	        left join price_group as pg on pg.id=pgs.id_price_group
			inner join cat c on c.pref = price.pref
			".$sJoin."
			where 1=1
			".$sWhere."
			group by price.id
			".$sOrder;


	
	

	return $sSql;
}
?>
