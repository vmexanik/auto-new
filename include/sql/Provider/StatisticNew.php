<?
function SqlProviderStatisticNewCall($aData) {

	$sSql="select up.*, up.name as provider_name
			, sum(cle.post-clw.post) as sum_time
			, count(c.id_provider) as count_row
		from cart c
		inner join user_provider as up on c.id_provider = up.id_user
		inner join cart_log as clw on (clw.id_cart = c.id and clw.order_status='work')
		inner join cart_log as cle on (cle.id_cart = c.id and cle.order_status in ('".$aData['order_status']."'))
		inner join cat as ca on c.cat_name=ca.name
		where 1=1
		".$where."
		group by c.id_provider";

	return $sSql;
}
?>