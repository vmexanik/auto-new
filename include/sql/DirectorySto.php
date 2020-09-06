<?
function SqlDirectoryStoCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ds.id='{$aData['id']}'";
	}
	if ($aData['id_directory_city_array']) {
		$sWhere.=" and ds.id_directory_city in (".implode(',',$aData['id_directory_city_array']).")";
	}
	if ($aData['visible']) {
		$sWhere.=" and ds.visible={$aData['visible']}";
	}
	if ($aData['order']) {
		$sOrder.=$aData['order'];
	}
	if ($aData['limit']) {
		$sLimit.=$aData['limit'];
	}

	$sSql="select u.*,uc.*,ds.*, dc.name as city_name
			from directory_sto as ds
			inner join directory_city as dc on ds.id_directory_city=dc.id
			left join user as u on ds.id_user=u.id
			left join user_customer as uc on ds.id_user=uc.id_user
			where 1=1
			".$sWhere."
			group by ds.id
			".$sOrder." ".$sLimit;

	return $sSql;
}
?>