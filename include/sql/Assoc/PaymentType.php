<?
function SqlAssocPaymentTypeCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by pt.name ";
	}

	if ($aData['multiple']) {
		$sField.=", pt.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and pt.visible='".$aData['visible']."'";
	}

	$sSql="select pt.id , pt.name
		".$sField."
	from payment_type as pt
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>