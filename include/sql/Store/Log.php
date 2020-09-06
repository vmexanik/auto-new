<?
function SqlStoreLogCall($aData) {

	$sWhere.=$aData['where'];
	
	if($aData['no_group']){
		$sFiels=",sl.count";
		$sGroup="";
	} else {
		$sFiels=", SUM(sl.count) AS count";
		$sGroup="group by id_from,id_to,id_product,price,md5_code";
	}

	$sSql="select t.* from (
		select sl.id, sl.id_user, sl.id_from, sl.id_to, sl.id_order, sl.id_product,
		sl.price, sl.tax, sl.md5_code, sl.post_date, sl.is_reserved,
		s.name as store_from, concat(c.name,' ',p.code,' ',p.name) as product".$sFiels."
		from store_log as sl
		inner join store_products as p on p.id=sl.id_product
		inner join store as s on sl.id_from=s.id
		inner join cat as c on c.pref=p.pref
		where sl.id_to='".$aData['store']."'
	".$sWhere."
	".$sGroup."
	".$aData['order'].") as t where t.count>0";

	return $sSql;
}
?>