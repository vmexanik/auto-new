<?
function SqlAssocUserCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and u.visible=1";
	}
	
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by u.login ";
	}
	
	if ($aData['type_']) {
		$sWhere.=" and u.type_='".$aData['type_']."'";
	} 
	
	if ($aData['id_user']) {
		$sWhere.=" and u.id='".$aData['id_user']."'";
	} 
	
	if ($aData['multiple']) {
		$sField.=", u.*";
	}

	$sSql="	select u.id , u.login as name "
	.$sField.
	"from user as u 
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>