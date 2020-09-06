<?
function SqlStoreHistoryCall($aData) {

	$sWhere.=$aData['where'];
	$sOrder.=$aData['order'];
	
	if($aData['count']) {
		$sField="count(*)";
		$sJoin="";
	} else {
		$sField="sl.*, sf.name as store_from, st.name as store_to, concat(c.name,' ',p.code,' ',p.name) as product";
		$sJoin="inner join cat as c on c.pref=p.pref";
	}

	$sSql="select ".$sField."
		from store_log as sl
		inner join store as sf on sl.id_from=sf.id
		inner join store as st on sl.id_to=st.id
		left join store_products as p on p.id=sl.id_product
		".$sJoin."
		where 1=1 and sl.count<>0 ".$sWhere."
		order by ".$sOrder;

	return $sSql;
}
?>