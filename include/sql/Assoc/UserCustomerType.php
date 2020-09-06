<?
function SqlAssocUserCustomerTypeCall($aData)
{
	$sWhere=$aData['where'];

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and uct.visible=1";
	}

	if ($aData['multiple']) {
		$sField.=", uct.*";
	}

	if ($aData['id']) {
		$sWhere.=" and uct.id='".$aData['id']."'";
	}

	$sSql="select uct.id , uct.name
		".$sField."
	from user_customer_type as uct
	where 1=1
	".$sWhere
	.$aData['order'];

	return $sSql;
}
?>