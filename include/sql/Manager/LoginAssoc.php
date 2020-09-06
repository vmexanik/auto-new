<?
function SqlManagerLoginAssocCall($aData) {

	$sSql="select u.login as id, u.login
		from user u  where u.type_='manager'";

	return $sSql;
}
?>