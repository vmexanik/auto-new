<?
function SqlAssocUserProviderCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and u.visible=1";
	}
	
	if ($aData['where']) {
		$sWhere .= $aData['where']; 	
	}
	
	if ($aData['is_auction']) {
		$sWhere.=" and up.is_auction=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by up.name ";
	}
	
	if ($aData['multiple']) {
		$sField.=", up.*, u.login, u.email ";
	}

	$sSql=" select up.id_user as id, up.name "
	.$sField.
 	" from user_provider as up
	inner join user as u on up.id_user=u.id
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>