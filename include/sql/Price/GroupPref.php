<?
function SqlPriceGroupPrefCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','pgs');

	if ($aData['join_price']) {
		$sGroup.=" group by c.id ";
	}
	
	if ($aData['id_price_group']) {
	    $sWhere.=" and pgs.id_price_group in(".$aData['id_price_group'].") ";
	}
	else
	    $sWhere.=' and 0=1 ';

	$sSql="	select distinct c.title as c_title, c.name as c_name, pg.name as pg_name, pg.code_name as pg_code_name
	from price_group_assign as pgs
	inner join cat as c on pgs.pref=c.pref
	inner join price_group as pg on pgs.id_price_group=pg.id
	inner join price as p on p.price>0 and p.item_code=pgs.item_code
	where 1=1
	".$sWhere
	.$sGroup
	.$aData['order'];

	return $sSql;
}
?>