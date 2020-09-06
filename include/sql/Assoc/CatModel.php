<?
function SqlAssocCatModelCall($aData) {
	
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
		$sWhere.=" and ".DB_TOF."tof__models.mod_id = ".$aData['id_model'];
	}
	
	if ($aData['sOrder']) {
		$sOrder=$aData['sOrder'];
	}
	
	if (0) {
		$sJoin=" inner join cat_model as cm on ".DB_TOF."tof__models.mod_id=cm.tof_mod_id and visible=1";
	}
		
	$sSql="select ".DB_TOF."tof__models.mod_id, ifnull(lng_tex.tex_text, uni_tex.tex_text) as name
    from ".DB_TOF."tof__models
    inner join cat as c on tof__models.mod_mfa_id=c.id_tof
    left outer join ".DB_TOF."tof__country_designations uni_des
      on mod_cds_id = uni_des.cds_id
     and uni_des.cds_lng_id = @lng_id
     and substring(uni_des.cds_ctm, @cou_id,1) = 1 

    left outer join ".DB_TOF."tof__des_texts uni_tex
      on uni_des.cds_tex_id = uni_tex.tex_id   

    left outer join ".DB_TOF."tof__country_designations lng_des
      on mod_cds_id = lng_des.cds_id
     and lng_des.cds_lng_id = @lng_id
     and substring(lng_des.cds_ctm, @cou_id,1) = 1

    left outer join ".DB_TOF."tof__des_texts lng_tex
      on lng_des.cds_tex_id = lng_tex.tex_id   
      ".$sJoin."
 	where 1=1
    and ( ".DB_TOF."tof__models.mod_pc = 1 or ".DB_TOF."tof__models.mod_cv = 1 )  
    and ( substring(mod_pc_ctm,@cou_id,1) = 1 or substring(mod_cv_ctm,@cou_id,1) = 1)
    ".$sWhere
    .$sOrder;

	return $sSql;
}
?>