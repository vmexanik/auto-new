<?
function SqlRatingCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and r.id='".$aData['id']."'";
	}
	if ($aData['section']) {
		$sWhere.=" and r.section='".$aData['section']."'";
	}

	$sSql="select r.*
			from rating as r
			where 1=1
				".$sWhere
			." "
			.$aData['order'];

	return $sSql;
}
?>