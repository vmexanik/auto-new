<?
function SqlPriceGroupTemplateCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','pgt');
	Db::SetWhere($sWhere,$aData,'code','pgt');
	Db::SetWhere($sWhere,$aData,'visible','pgt');

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	$sSql="	select pgt.*
	from price_group_template as pgt
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>