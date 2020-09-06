<?
function SqlStoreCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['order']) {
		$sOrder.=" order by ".$aData['order'];
	}
	
	if ($aData['id']) {
		$sWhere.=" and s.id=".$aData['id'];
	}

	$sSql="select s.*, up.name as up_name
			from store as s
			left join user_provider as up on s.id_provider=up.id_user
			where 1=1
			".$sWhere."
			group by s.id
			".$sOrder;

	/*$sSql="select s.*, group_concat(up.name) as up_name
			from store as s
			left join store_provider as sp on s.id=sp.id_store
			left join user_provider as up on sp.id_user_provider=up.id_user
			where 1=1
			".$sWhere."
			group by s.id
			".$sOrder;*/

//Debug::PrintPre($sSql,false);
	return $sSql;
}
?>