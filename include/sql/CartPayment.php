<?php

function SqlCartPaymentCall($aData)
{
	$sWhere .= $aData['where'];

	$sSql="select cp.*
			,c.code as cart_code,c.id_user
        from cart_payment as cp
        	inner join cart as c on cp.id_cart = c.id
            inner join user_customer as uc on c.id_user = uc.id_user
            inner join user as u on uc.id_user = u.id
		where 1=1
		      ".$sWhere;

	return $sSql;
}

?>
