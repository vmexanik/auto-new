<?
function SqlPriceGroupAssignCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','pgs');
	Db::SetWhere($sWhere,$aData,'item_code','pgs');
	Db::SetWhere($sWhere,$aData,'id_price_group','pgs');
	
	Db::SetWhere($sWhere,$aData,'code','p');
	Db::SetWhere($sWhere,$aData,'pref','p');
	Db::SetWhere($sWhere,$aData,'part_rus','pg');

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	$sSql="	select pgs.*, p.code,p.pref,p.part_rus, c.title as brand
	from price_group_assign as pgs
			left join price as p on p.item_code=pgs.item_code
			inner join cat as c on c.pref=p.pref
	where 1=1
	".$sWhere
	. $sOrder." group by pgs.item_code, pgs.id_price_group";

	return $sSql;
}
?>