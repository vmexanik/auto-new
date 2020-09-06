<?
function SqlProviderStatisticCall($aData) {

	//	if ($aData['id']) {
	//		$sWhere.=" and pr.id='{$aData['id']}'";
	//	}
	$sWhere.=$aData['where'];


	$sSql="select u.*, up.*, ps.*
		from provider_statistic as ps
		inner join user_provider as up on ps.id_user = up.id_user
		inner join user as u on (u.id=ps.id_user and u.type_='provider')
		where 1=1
		".$sWhere."
		group by ps.id_user, ps.make";

	return $sSql;
}
?>