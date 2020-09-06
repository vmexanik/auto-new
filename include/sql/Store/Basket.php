<?
function SqlStoreBasketCall($aData) {

	$sWhere.=$aData['where'];
	
	if($aData['type']!='1'){
		$sField=", s.name as store_from";
		$sJoin="inner join store as s on b.id_from=s.id";
	}

	$sSql="select b.*, concat(c.name,' ',p.code,' ',p.name) as product".$sField."
		from store_basket as b
		inner join store_products as p on p.id=b.id_product
		".$sJoin."
		inner join cat as c on c.pref=p.pref
		where b.type='".$aData['type']."' and b.id_user='".Auth::$aUser['id']."' 
	".$sWhere;

	return $sSql;
}
?>