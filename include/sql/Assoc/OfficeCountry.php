<?
function SqlAssocOfficeCountryCall($aData)
{
	$sWhere=$aData['where'];

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and oc.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	if ($aData['multiple']) {
		$sField.=", oc.*";
	}

	if ($aData['id']) {
		$sWhere.=" and oc.id='".$aData['id']."'";
	}

	$sSql="select oc.id , oc.name
		".$sField."
	from office_country as oc
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>