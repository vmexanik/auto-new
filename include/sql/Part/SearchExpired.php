<?
function SqlPartSearchExpiredCall($aData) {

	$sWhere=$aData['where'];

	$sSql="select c.*, cat.title as brand
			 from cart_deleted c
			 left join cat on cat.pref = c.pref
			where 1=1
			".$sWhere."
			group by c.id ";

	return $sSql;
}
?>