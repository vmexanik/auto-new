<?
function SqlAssocPriceProfileCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by pp.num ";
	}

	if ($aData['multiple']) {
		$sField.=", pp.*";
	}
	
	$sSql="select pp.id , pp.name
		".$sField."
	from price_profile as pp
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>