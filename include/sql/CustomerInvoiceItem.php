<?
function SqlCustomerInvoiceItemCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ii.id=".$aData['id'];
	}

	if ($aData['user']) {
		$sWhere = " and ii.user='" . addslashes ( $aData ['user'] )."'";
	}
	if ($aData['invoice_name']) {
		$sWhere = " and ii.invoice_name='" . addslashes ( $aData ['invoice_name'] )."'";
	}

	$sSql="select ii. *
			from invoice_item ii
			where 1=1
			".$sWhere;

	return $sSql;
}
?>