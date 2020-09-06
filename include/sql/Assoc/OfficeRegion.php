<?
function SqlAssocOfficeRegionCall($aData)
{
	$sWhere=$aData['where'];

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and or.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	if ($aData['multiple']) {
		$sField.=", or.*";
	}

	if ($aData['id']) {
		$sWhere.=" and or.id='".$aData['id']."'";
	}

	$sSql="select or.id , or.name
		".$sField."
	from office_region as `or`
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>