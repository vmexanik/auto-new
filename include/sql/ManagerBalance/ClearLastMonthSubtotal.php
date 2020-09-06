<?
function SqlManagerBalanceLastMonthSubtotalCall($aData=array()) {

	$sSql="delete from log_balance as lb
		where lb.post_date >= DATE_FORMAT(now(), '%Y-%m-01') and subtotal_year>0 and subtotal_month>0";

	return $sSql;
}
?>
