<?
function SqlDeliveryTypeCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere, $aData, 'id', 'dt');
	Db::SetWhere($sWhere, $aData, 'visible', 'dt');

	$sSql="select dt.*
			from delivery_type dt
			where 1=1
				".$sWhere;

	return $sSql;
}
?>