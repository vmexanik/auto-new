<?
function SqlPriceProfileCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere, $aData, 'name', 'pp');
	Db::SetWhere($sWhere, $aData, 'id', 'pp');

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	if ($aData['assoc']) {
		$sSql="	select pp.id, pp.name ";
	} else {
		$sSql="	select pp.*
		, if(pref='', col_cat, pref) as cat__pref "
		.$sField;
	}

	$sSql.=
	" from price_profile as pp
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>