<?
function SqlAssocOptiCatModelCall($aData) {
	
	if ($aData['multiple']) {
		$sField.=", tm.*";
	}
	
	if ($aData['id_make']) 
	{
		$sWhere.=" and c.id = ".$aData['id_make'];
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
	
	if ($aData['check_visible']) {
		$sJoin=" inner join cat_model as cm on ".DB_OCAT."cat_alt_models.ID_src=cm.tof_mod_id and cm.visible=1 ";
	}
		
	$sSql="select cat_alt_models.ID_src, cat_alt_models.Name
    from ".DB_OCAT."cat_alt_models
	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
    inner join cat as c on cat_alt_manufacturer.ID_src=c.id_tof
      ".$sJoin."
 	where 1=1
    ".$sWhere
    .$sOrder;

	return $sSql;
}
?>