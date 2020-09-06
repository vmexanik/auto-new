<?
function SqlPaymentTypeCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pt.id='".$aData['id']."'";
	}

	$sSql="select pt.*
			from payment_type pt
			where 1=1
				".$sWhere
				." ".$aData['order'];

	return $sSql;
}
?>