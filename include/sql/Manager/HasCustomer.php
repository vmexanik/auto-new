<?
function SqlManagerHasCustomerCall($aData) {

	$sSql="select u.*, um.*
			from user u
			inner join user_manager um on u.id=um.id_user
			where u.visible=1
				and (um.has_customer=1 or um.id_customer_partner>0)";

	return $sSql;
}
?>