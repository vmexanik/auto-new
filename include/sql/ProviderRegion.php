<?
function SqlProviderRegionCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pr.id='".$aData['id']."'";
	}
//	if ($aData['join_manager']) {
//		$sJoin.=" left join user_manager_region as umr on (umr.id_provider_region=pr.id and umr.id_user='".$aData['id_user']."')";
//		$sField.=" , umr.id_user as region_allowed";
//	}

	$sSql="select pr.*, ifnull(prw.name,'not') as prw_name
				".$sField."
			from provider_region pr
			left join provider_region_way as prw on pr.id_provider_region_way=prw.id
				".$sJoin."
			where 1=1
				".$sWhere."
			group by pr.id";

	return $sSql;
}
?>