<?
function SqlStoreLogBalanseCall($aData) {
	$sWhere=$aData['where'];
	
	if($aData['direction']) {
		$sDir1="from";
		$sDir2="to";
	} else {
		$sDir1="to";
		$sDir2="from";
	}

	$sSql="select sl.*, s.name as store_".$sDir1.", concat(c.name,' ',p.code,' ',p.name) as product
		,sl.count*sl.price as summ
		,(sl.count*sl.price)-((sl.count*sl.price)*(100-sl.tax)/100) as summ_tax
		from store_log as sl
		inner join store_products as p on p.id=sl.id_product
		inner join store as s on sl.id_".$sDir1."=s.id
		inner join cat as c on c.pref=p.pref
		where sl.id_".$sDir2." in ('".$aData['stores']."')
		and sl.count>0 ".$sWhere;

	return $sSql;
}
?>