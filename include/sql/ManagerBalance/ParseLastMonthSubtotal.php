<?
function SqlManagerBalanceLastMonthSubtotalCall($aData=array()) {

	$sSql="insert into log_balance(
			hrn_nal_credit,
			hrn_nal_debet,
			hrn_beznal_credit,
			hrn_beznal_debet,
			usd_nal_credit,
			usd_nal_debet,
			usd_beznal_credit,
			usd_beznal_debet,
			eur_nal_credit,
			eur_nal_debet,
			eur_beznal_credit,
			eur_beznal_debet,
			aed_nal_credit,
			aed_nal_debet,
			aed_beznal_credit,
			aed_beznal_debet,
			subtotal,
			subtotal_year,
			subtotal_month,
			user_name)
		select
			sum(lb.hrn_nal_credit),
			sum(lb.hrn_nal_debet),
			sum(lb.hrn_beznal_credit),
			sum(lb.hrn_beznal_debet),
			sum(lb.usd_nal_credit),
			sum(lb.usd_nal_debet),
			sum(lb.usd_beznal_credit),
			sum(lb.usd_beznal_debet),
			sum(lb.eur_nal_credit),
			sum(lb.eur_nal_debet),
			sum(lb.eur_beznal_credit),
			sum(lb.eur_beznal_debet),
			sum(lb.aed_nal_credit),
			sum(lb.aed_nal_debet),
			sum(lb.aed_beznal_credit),
			sum(lb.aed_beznal_debet),
			sum(lb.subtotal),
			DATE_FORMAT(now() - interval 1 month, '%Y') as subtotal_year,
			DATE_FORMAT(now() - interval 1 month, '%m') as subtotal_month,
			lb.user_name
		from log_balance as lb
		where lb.post_date >=DATE_FORMAT(now() - interval 1 month, '%Y-%m-01') and lb.post_date < DATE_FORMAT(now(), '%Y-%m-01')
		group by lb.user_name";

	return $sSql;
}
?>
