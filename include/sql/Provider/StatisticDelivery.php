<?
function SqlProviderStatisticDeliveryCall($aData=array()) {

	$sSql="insert into provider_statistic (make,id_user,delivery_term)
			select ca.name,up.id_user, round(sum(cle.post-clw.post)/count(*)/86400,2) as delivery_term
			from cart c
			inner join user_provider as up on c.id_provider = up.id_user
			inner join cart_log as clw on (clw.id_cart = c.id and clw.order_status='work')
			inner join cart_log as cle on (cle.id_cart = c.id and cle.order_status='store')
			inner join cat as ca on c.cat_name=ca.name
			where 1=1
			group by c.id_provider, ca.name
			ON DUPLICATE KEY UPDATE provider_statistic.delivery_term=values(delivery_term)";

	return $sSql;
}
?>