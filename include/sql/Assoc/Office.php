<?
function SqlAssocOfficeCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by o.name ";
	}

	if ($aData['multiple']) {
		$sField.=", o.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and o.visible='".$aData['visible']."'";
	}

	$sSql="select o.id , o.name
		".$sField."
	from office as o
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>