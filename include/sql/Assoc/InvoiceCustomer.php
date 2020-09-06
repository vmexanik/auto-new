<?
function SqlAssocInvoiceCustomerCall($aData)
{
	$sWhere.=$aData['where'];

	$sSql="select ic.id , ic.*, ic.id as custom_id
	from invoice_customer as ic
	where 1=1
	".$sWhere;

	return $sSql;
}
?>