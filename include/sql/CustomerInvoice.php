<?
function SqlCustomerInvoiceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ci.id=".$aData['id'];
	}

	if ($aData['user']) {
		$sWhere .= " and ci.user='" . addslashes ( $aData ['user'] )."'";
	}

	if ($aData['invoice_name']) {
		$sWhere .= " and ci.invoice_name='" . addslashes ( $aData ['invoice_name'] )."'";
	}

	$sSql="select ci. *
			from customer_invoice ci
			where 1=1
			".$sWhere;

	return $sSql;
}
?>