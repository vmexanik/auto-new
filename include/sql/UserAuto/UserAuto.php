<?
function SqlUserAutoCall($aData) {

	$sWhere.=$aData['where'];
	if ($aData['id_user'])
		$iUid = $aData['id_user']; 
	elseif (Auth::$aUser['id'] == 0)
		$iUid = 0;
	else 
		$iUid = Auth::$aUser['id'];

	$sSql = "select u.*,ua.*,ua.id as ua_id, /*coalesce(cm.name,m.Name)*/ cm.name name_model
			from user_auto ua
			inner join user as u on ua.id_user=u.id
			left join cat_model as cm on ua.id_model=cm.tof_mod_id
			/*left join ".DB_OCAT."cat_alt_models m on m.ID_src=cm.tof_mod_id*/
			where 1=1 and id_user = ".$iUid." 
			".$sWhere;

	return $sSql;
}
?>