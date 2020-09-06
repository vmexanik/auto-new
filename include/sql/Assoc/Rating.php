<?
function SqlAssocRatingCall($aData)
{
	if ($aData['where']) $sWhere.=$aData['where'];

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by r.num ";
	}

	if ($aData['multiple']) {
		$sField.=", r.*";
	}

	if ($aData['section']) {
		$sWhere.=" and r.section='".$aData['section']."'";
	}

	$sSql="select r.num , r.name
		".$sField."
	from rating as r
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>