<?
function SqlCriteriaCall($aData) {
	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	if($aData['art_id']) {
		$inArtId = "'".implode("','",$aData['art_id'])."'";
		$sWhere .= "and ci.ID_art in (".$inArtId.")"; 
	} 
	
	if($aData['Name'])
		$sWhere .= " and ca.Name = '".$aData['Name']."'";
	
	$sSql="SELECT ci.ID_art as art_id, ca.Value as cri_text
	".$sField."
	FROM ".DB_OCAT."`cat_alt_link_art_inf` ci 
	left join ".DB_OCAT."`cat_alt_additions` as ca on ca.ID_add=ci.ID_add
	".$sJoin."
	where 1=1
  	".$sWhere;

	return $sSql;
}
?>