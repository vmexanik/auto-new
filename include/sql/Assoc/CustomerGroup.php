<?
function SqlAssocCustomerGroupCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by name ";
	}

	if ($aData['price_type']) {
		$sWhere.=" and cg.price_type='".$aData['price_type']."' ";
	}
	
	if ($aData['where']) {
		$sWhere.=$aData['where'];
	}

	$sSql="
	select cg.id as id, cg.name as name
 	from customer_group as cg
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>