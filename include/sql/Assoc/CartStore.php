<?
function SqlAssocCartStoreCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and cs.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by cs.name ";
	}
	
	if ($aData['multiple']) {
		$sField.=", cs.* ";
	}

	$sSql=" select cs.id, cs.name "
	.$sField.
 	" from cart_store as cs
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>