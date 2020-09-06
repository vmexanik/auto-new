<?
function SqlAssocCartCall($aData)
{
	$sWhere.=$aData['where'];

	$sSql="
		select c.id, c.*
	 	from cart as c
		where 1=1
		".$sWhere;

	return $sSql;
}
?>