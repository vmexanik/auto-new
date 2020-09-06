<?
function SqlAssocOfficeCityCall($aData)
{
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

	if ($aData['id_office_region']) {
		$sWhere.=" and oc.id_office_region='".$aData['id_office_region']."'";
	}

	$sSql="select oc.id , oc.name
		".$sField."
	from office_city as oc
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>