<?
function SqlAssocPriceRequestStatusCall($aData) {

//	if ($aData['all']) {
//		$sWhere.=" ";
//	} else {
//		$sWhere.=" and c.visible=1";
//	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by name ";
	}

	$sSql="
	select id, name
 	from price_request_status as prs
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>