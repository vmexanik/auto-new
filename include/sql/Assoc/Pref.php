<?
function SqlAssocPrefCall($aData) {

//	if ($aData['all']) {
//		$sWhere.=" ";
//	} else {
//		$sWhere.=" and c.visible=1";
//	}

	if ($aData['is_brand']) {
		$sWhere.=" and is_brand=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by name ";
	}

	
		
	$sSql="
	select pref, title as name
 	from cat as c
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>