<?
function SqlProviderAssocCall($aData) {

	$sWhere.=$aData['where'];

	$sSql="select u.id, up.name
		from user_provider as up
		inner join user as u on (u.id=up.id_user and u.type_='provider')
		where 1=1
		".$sWhere;

	return $sSql;
}
?>