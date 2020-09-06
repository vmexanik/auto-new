<?php
function SqlLogDebtCall($aData) {

    $sWhere .= $aData['where'];

    if ($aData['id']) {
        $sWhere .= " and ld.id='{$aData['id']}'";
    }

    $sSql="select u.login as user_login, ld.*
			from log_debt as ld
			inner join user as u on ld.id_user = u.id
			where 1=1
				" . $sWhere;

    return $sSql;
}

?>
