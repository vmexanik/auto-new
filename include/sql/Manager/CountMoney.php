<?
function SqlManagerCountMoneyCall($aData) {

	$sWhere.=$aData['where'];

	$sQuery = "select u.login as customer_login, c. *
				from cart c
				inner join user_customer as uc on c.id_user = uc.id_user
				inner join user as u on uc.id_user = u.id
				inner join customer_group as cg on uc.id_customer_group = cg.id
				inner join cart_log as cl on (c.id = cl.id_cart and cl.order_status = 'end')
				where c.type_ = 'order' and c.order_status = 'end' and c.price > 0
				".$sWhere."
				group by c.id";

	$sSumSql = "SELECT SUM(s.number * s.price) AS sum FROM (".$sQuery.") s";

	return $sSumSql;
}
?>