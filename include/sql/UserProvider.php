<?
function SqlUserProviderCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.=" and up.id_user='".$aData['id_user']."'";
	}

	if ($aData['is_our_store']) {
		$sWhere.=" and up.is_our_store='".$aData['is_our_store']."'";
	}
	
	$sOrder = "Order by up.name";
	if ($aData['order'])
		$sOrder = $aData['order'];
	
	$sSql="select pr.*,up.*, up.id_user as id
		 , u.login, u.password, u.visible, u.approved, u.email
		 , pr.code_delivery
	 from user_provider as up
	 inner join user as u on u.id=up.id_user
	 inner join provider_region as pr on up.id_provider_region=pr.id
	 where 1=1 ".$sWhere." ".$sOrder;
	return $sSql;
}
?>