<?
function SqlAssocDeliveryTypeCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and dt.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by dt.name ";
	}

	if (!$aData['field']) {
		$sField.=" , dt.name ";
	}
	else $sField.=$aData['field'];

	if ($aData['multiple']) {
		$sField.=", dt.*";
	}


	$sSql="select dt.id 
			".$sField."
			from delivery_type dt
			where 1=1
		".$sWhere
	.$sOrder;

	return $sSql;
}
?>