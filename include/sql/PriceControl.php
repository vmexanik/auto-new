<?
function SqlPriceControlCall($aData) {

    
    /*if(Auth::$aUser['is_super_manager'])
        $sWhereManager = ' ';
    else
        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";*/
    
	// error
	$sError = "(pi.price=0 or pi.id_provider=0 or pi.id_provider is null or pi.code='' or pi.pref='' or pi.pref is null or pi.is_code_ok=0) ";
	// stock exist and not error
	if (Language::getConstant('price_control:ignore_load_empty_stock',0))
	    $sStockExist =" and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(pi.stock,'>',''),'<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 ";

	$sWhere = " and (".$sError.$sStockExist.") ";
	// end work
	$sWhere .= " and (pq.is_processed=2 or pq.is_processed=3) ";
	
	$sWhere.=$aData['where'];

	/*if ($aData['id']) {
		$sWhere.=" and b.id='{$aData['id']}'";
	}*/
	$sGroup = " group by pi.id";

	$sSql="select pi.*, pq.file_name_original, pq.file_path, pq.post_date as pq_post_date, pq.sum_all as pq_sum_all,
			pq.sum_errors as pq_sum_errors, u.login as login_provider, pp.name as name_price_profile,
			pq.is_processed as pq_is_processed, pq.source as pq_source, u2.login as login_provider_buffer,
			cu.symbol as currency_provider, up.name as name_store,
	        c.id_tof
			from price_import as pi
			inner join price_queue pq on pq.id = pi.id_price_queue
			inner join user u on u.id = pq.id_user_provider 
			inner join price_profile pp on pp.id = pq.id_price_profile
			inner join user u2 on u2.id = pi.id_provider
			inner join user_provider up on up.id_user = u2.id
			inner join currency as cu on cu.id = up.id_currency
			left join cat c on c.pref = pi.pref
			where 1=1
				".$sWhere.$sGroup;

	return $sSql;
}
?>