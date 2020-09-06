<?
function SqlProviderStatisticStatusCall($aData=array()) {

	$sSql="insert into provider_statistic (make,id_user,refuse_percent)
		select c.cat_name as make, up.id_user, round(100*sum(if(cl.order_status='refused',1,0))/t.num_cart,2) as refuse_percent
		from cart as c
		inner join (
		       select c.id_provider,c.cat_name, count(*) as num_cart
		       from cart as c
		       group by c.id_provider,c.cat_name
		           ) t on t.id_provider=c.id_provider and t.cat_name=c.cat_name
		inner join user_provider as up on c.id_provider = up.id_user
		inner join cart_log as cl on cl.id_cart = c.id
		inner join cat as ca on c.cat_name=ca.name
		where 1=1
		group by c.id_provider,c.cat_name
		ON DUPLICATE KEY UPDATE provider_statistic.refuse_percent=values(refuse_percent)";

	return $sSql;
}
?>
