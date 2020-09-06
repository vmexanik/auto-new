<?
function SqlAssocPriceGroupCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by pg.name ";
	}

	if ($aData['multiple']) {
		$sField.=", pg.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and pg.visible='1'";
	}

	$sSql="select pg.id , pg.name
		".$sField."
	from price_group as pg
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>