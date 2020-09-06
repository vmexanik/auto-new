<?
function SqlVinRequestCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and vr.id='{$aData['id']}'";
	}
	if ($aData['id_in']) {
		$sWhere.=" and vr.id in (".$aData['id_in'].")";
	}
	if ($aData['id_manager_fixed']) {
		$sWhere.=" and vr.id_manager_fixed='{$aData['id_manager_fixed']}'";
	}
	if ($aData['refuse_for']) {
		$sWhere.=" and vr.refuse_for='{$aData['refuse_for']}'";
	}

	$sSql="select cg.*,u.*,uc.*,vr.*, u.login, m.login as manager_login
				,um.name as manager_name, um.phone as manager_phone
				,dt.name as delivery_type_name, cat.title as name_marka
				,cm.name as name_model
			from vin_request vr
			inner join user u on vr.id_user=u.id
			inner join user_customer uc on uc.id_user=u.id
			inner join customer_group cg on uc.id_customer_group=cg.id
			inner join user m on uc.id_manager=m.id
			inner join user_manager um on m.id=um.id_user
			left join delivery_type as dt on vr.id_delivery_type=dt.id
			left join cat on cat.id = vr.id_make
			left join cat_model cm on cm.tof_mod_id = vr.id_model
			where 1=1
				".$sWhere;
	return $sSql;
}
?>