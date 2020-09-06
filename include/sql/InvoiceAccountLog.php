<?
function SqlInvoiceAccountLogCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.=" and ial.id_user='".$aData['id_user']."'";
	}

	$sSql="select ial.*
			from invoice_account_log as ial
			where 1=1
			".$sWhere.$aData['order'];

	return $sSql;
}
?>