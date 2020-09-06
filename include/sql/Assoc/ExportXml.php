<?
function SqlAssocExportXmlCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by ex.name ";
	}

	if ($aData['multiple']) {
		$sField.=", ex.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and ex.visible='".$aData['visible']."'";
	}

	$sSql="select ex.code , ex.name
		".$sField."
	from export_xml as ex
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>