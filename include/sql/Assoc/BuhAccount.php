<?
function SqlAssocBuhAccountCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by b.id ";
	}

	if ($aData['multiple']) {
		$sField.=", b.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and b.visible='".$aData['visible']."'";
	}

	$sSql="select b.id , concat(id,' - ', name) as name
		".$sField."
	from buh as b
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>