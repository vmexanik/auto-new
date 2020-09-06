<?php

function SqlLogVisitCall($aData) {

    $sWhere .= $aData['where'];

    if ($aData['id']) {
        $sWhere .= " and lv.id='{$aData['id']}'";
    }

    $sSql="select u.login as user_login, lv.*
			from log_visit as lv
			inner join user as u on lv.id_user = u.id
			where 1=1
				" . $sWhere;

    return $sSql;
}

?>
