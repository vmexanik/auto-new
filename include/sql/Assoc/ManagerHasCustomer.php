<?
function SqlAssocManagerHasCustomerCall($aData) {

	$sSql="select u.id, u.login
			from user u
			inner join user_manager um on u.id=um.id_user
			where u.visible=1
				and (um.has_customer=1 or um.id_customer_partner>0)";

	return $sSql;
}
?>