<?
function SqlStoreProductsCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','sp');
	Db::SetWhere($sWhere,$aData,'code','sp');
	Db::SetWhere($sWhere,$aData,'name','sp');
	Db::SetWhere($sWhere,$aData,'pref','sp');

	$sSql="select sp.*, c.name as cat, c.pref
	from store_products as sp
	left join cat as c on c.pref=sp.pref
	where 1=1
	".$sWhere;

	return $sSql;
}
?>