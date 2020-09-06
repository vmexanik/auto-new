<?
function SqlAssocCurrencyCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and c.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by c.num ";
	}

	if ($aData['type_']=="id" || !$aData['type_']) {
		$sField="c.id , c.name ";
	} else {
		$sField="c.code , c.name ";
	}

	if ($aData['multiple']) {
		$sField.=", c.*, round(1/value,2) as reciprocal";
	}

	$sSql="	select
	".$sField."
	from currency as c
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>