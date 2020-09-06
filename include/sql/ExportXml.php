<?
function SqlExportXmlCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ex.id='".$aData['id']."'";
	}

	$sSql="select ex.*
			from export_xml as ex
			where 1=1
				".$sWhere
			." "
			.$aData['order'];

	return $sSql;
}
?>