<?
function SqlAssocUserManagerCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and u.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by name ";
	}

	$sSql="
	select id_user as id, concat(login,'::', name) as name
 	from user_manager as um
	inner join user as u on um.id_user=u.id
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>