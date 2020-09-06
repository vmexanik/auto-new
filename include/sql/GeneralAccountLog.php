<?php
function SqlGeneralAccountLogCall($aData) {

	$sWhere .= $aData['where'];

	//    if ($aData['id']) {
	//        $sWhere .= " and gal.id='{$aData['id']}'";
	//    }

	$sSql="select u.*, ual.*, gal.*
    			, ualt.name as user_account_log_type_name
			from general_account_log as gal
			inner join user_account_log as ual on  gal.id_user_account_log = ual.id
			inner join user as u on ual.id_user = u.id
			inner join user_account_log_type as ualt on ual.id_user_account_log_type=ualt.id
			where 1=1
				" . $sWhere;

	return $sSql;
}

?>
