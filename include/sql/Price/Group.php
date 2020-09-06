<?
function SqlPriceGroupCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','pg');
	Db::SetWhere($sWhere,$aData,'code','pg');
	Db::SetWhere($sWhere,$aData,'name','pg');
	Db::SetWhere($sWhere,$aData,'code_name','pg');
	Db::SetWhere($sWhere,$aData,'visible','pg');

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	$sSql="	select pg.*
	from price_group as pg
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>