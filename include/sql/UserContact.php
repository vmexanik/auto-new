<?
function SqlUserContactCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and uc.id='".$aData['id']."'";
	}
	
	if ($aData['id_user']) {
		$sWhere.=" and uc.id_user='".$aData['id_user']."'";
	}

	if ($aData['id_user_contact_type']) {
		$sWhere.=" and uc.id_user_contact_type='".$aData['id_user_contact_type']."'";
	}

	if ($aData['name']) {
		$sWhere.=" and uc.name like '%".$aData['name']."%'";
	}
	
	if ($aData['id_package_sending']) {
		$sField.=" , sum(ifnull(p.weight_custom,0)) as weight, sum(ifnull(p.price,0)) as price";
		$sJoin.=" left join package as p on uc.id=p.id_user_contact and p.id_package_sending=".$aData['id_package_sending'];
	}

	$sSql="select uc.*
		, uct.name as uct_name
		, u.login as login
		, ifnull(ofr.name,'') as region
		, ifnull(oc.name,'') as city_name
		, ifnull(uc.address, concat(uc.street,' ', ifnull(uc.house,''),' ', ifnull(uc.housing,''),' ', ifnull(uc.apartment,''))) as address
		".$sField."
			from user_contact uc
			inner join user_contact_type as uct on uc.id_user_contact_type=uct.id
			inner join user as u on uc.id_user=u.id
			left join office_region as ofr on uc.id_region=ofr.id
			left join office_city as oc on uc.id_city=oc.id
		".$sJoin."	
		where 1=1
		".$sWhere."
		group by uc.id";

	return $sSql;
}
?>