<?
function SqlAssocCustomerCartCall($aData)
{
	$sWhere.=$aData['where'];

	$sSql="
		select c.id_user, sum(c.price*c.number)
	 	from cart as c
		where 1=1
			".$sWhere."
		group by c.id_user
		";

	return $sSql;
}
?>