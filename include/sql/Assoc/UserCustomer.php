<?
function SqlAssocUserCustomerCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and u.visible=1";
	}

	$sSql="
	select id as id, login
 	from user as u
 	inner join user_customer as uc on u.id=uc.id_user
	where 1=1
	".$sWhere;

	return $sSql;
}
?>