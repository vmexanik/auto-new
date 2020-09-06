<?php
function SqlCartLogCall($aData) {

    $sWhere .= $aData['where'];

    $sSql="select u.login as customer_login, c.*
        from cart c
            inner join user_customer uc on c.id_user = uc.id_user
            inner join user u on uc.id_user = u.id
	        ".$aData['join']."
		where c.type_='order'
		      and c.order_status not in ('refused','pending')
			  and price > 0
			  and c.login_vin_request != ''
		      ".$sWhere."
		group by c.id";

    return $sSql;
}
?>
