<?
function SqlAssocOptiCatModelPicCall($aData) {
	
	if ($aData['multiple']) {
		$sField.=", tm.*";
	}
	
	if ($aData['id_tof']) 
	{
		$sWhere.=" and ca.ID_src = ".$aData['id_tof'];
	}
	else 
	{
		$sWhere.=" and 1=0";
	}
	
	if ($aData['id_model']) 
	{
		$sWhere.=" and ".DB_OCAT."cat_alt_models.ID_src = ".$aData['id_model'];
	}
	
	if ($aData['sOrder']) {
		$sOrder=$aData['sOrder'];
	}
	
		
	$sSql="select cat_alt_models.ID_src, cat_alt_models.Name
	from ".DB_OCAT."cat_alt_models
	inner join ".DB_OCAT."cat_alt_manufacturer ca on cat_alt_models.ID_mfa=ca.ID_mfa
	/*inner join cat as c on cat_alt_manufacturer.ID_src=c.id_tof*/
	inner join model_pic as mp on mp.id_tof=ca.ID_src
      ".$sJoin."
 	where 1=1
    ".$sWhere
    .$sOrder;

	return $sSql;
}
?>