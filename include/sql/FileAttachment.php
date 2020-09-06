<?
function SqlFileAttachmentCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and fa.id='".$aData['id']."'";
	}

	if ($aData['table_name']) {
		$sWhere.=" and fa.table_name='".$aData['table_name']."'";
	}

	if ($aData['table_id']) {
		$sWhere.=" and fa.table_id='".$aData['table_id']."'";
	}

	$sSql="select fa.*, concat(path, id, '_', file_name) as file_path
		".$sField."
	from file_attachment as fa
		".$sJoin."
	where 1=1
		".$sWhere
	;

	return $sSql;
}
?>