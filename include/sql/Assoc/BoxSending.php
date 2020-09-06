<?
function SqlAssocBoxSendingCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and cpb.visible=1";
	}
	
	if ($aData['inIdCartPackingBox']) {
		$sWhere.=" and cpb.id in (".$aData['inIdCartPackingBox'].")";
	}

	if ($aData['multiple']) {
		$sField.=" ";
	}

	$sSql=" select cpb.id as id, cs.id as id_cart_sending "
	.$sField.
 	" from cart_sending as cs
	inner join cart_packing_box as cpb on cs.id=cpb.id_cart_sending
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>