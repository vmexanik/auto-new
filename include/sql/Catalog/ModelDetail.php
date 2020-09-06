<?
function SqlCatalogModelDetailCall($aData) {
	
	if ($aData['id_model']) 
	{
		$sWhere.="and typ_mod_id = ".$aData['id_model'];
	}
	elseif ($aData["type_number"]) 
	{
		$sJoin=" inner join ".DB_TOF."tof__type_numbers as ttn on ".DB_TOF."tof__types.typ_id = ttn.tyn_typ_id ";
		$sWhere.=" and ttn.tyn_kind = 2  AND ttn.tyn_search_text like '".$aData["type_number"]."%' ";
	}
	elseif ($aData["code"] && $aData["art_id"]) 
	{
		$sJoin=" inner join ".DB_TOF."tof__link_la_typ_view on typ_id = lat_typ_id";
		$sWhere.=" and art_article_nr = '".$aData["code"]."' and art_id='".$aData["art_id"]."'";
	}
	else 
	{
		$sWhere="and 1=0";
	}
	
	if ($aData['id_model_detail']) 
	{
		$sWhere.=" and typ_id = ".$aData['id_model_detail'];
	}
	
	if ($aData['year']) 
	{
		$sWhere.=" and ifnull(tyc_pcon_start, typ_pcon_start)<=".$aData['year']."01"
		." and ifnull(ifnull(tyc_pcon_end, typ_pcon_end),999999)>=".$aData['year']."01";
	}
	
	
	if ($aData['volume']) 
	{
		$dVolume=str_replace(",",".",$aData['volume']);
		if ($dVolume<=100) {
			$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
			.($dVolume*1000);
		} else {
			$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
			.round($dVolume,-2);
		}
		
		//$sWhere.=" and substring(ifnull(tyc_ccm, typ_ccm),1,2)="
		//.substr(str_replace(array(",","."),"",$aData['volume']),0,2);
	}
	
	if ($aData['fuel']) 
	{
		$sWhere.=" and ifnull(tyc_kv_engine_des_id, typ_kv_engine_des_id)=".$aData['fuel'];
	}
		
		
	$sSql="select ifnull(lng_tex.tex_text, uni_tex.tex_text) name,
         ifnull(tyc_pcon_start, typ_pcon_start) pcon_start,   
         ifnull(tyc_pcon_end, typ_pcon_end) pcon_end,   
         ifnull(tyc_kw_from, typ_kw_from) kw_from,   
         ifnull(tyc_hp_from, typ_hp_from) hp_from,   
         ifnull(tyc_ccm, typ_ccm) ccm,   
         ifnull(tyc_bod_tex.tex_text, ifnull(bod_tex.tex_text, ifnull(tyc_mod_tex.tex_text, mod_tex.tex_text))) body,   
         ifnull(tyc_axl_tex.tex_text, axl_tex.tex_text) axis,   
         ifnull(tyc_max_weight, typ_max_weight) max_weight,   
         ifnull(tyc_kv_body_des_id, ifnull(typ_kv_body_des_id, ifnull(tyc_kv_model_des_id , typ_kv_model_des_id ))) body_des_id,
         ifnull(tyc_kv_engine_des_id, typ_kv_engine_des_id) engine_des_id,   
         ifnull(tyc_kv_axle_des_id, typ_kv_axle_des_id) axis_des_id,   
         typ_mod_id mod_id,   
         typ_id typ_id,   
         '              ' button, 
         substring(typ_la_ctm,@cou_id,1) flag_id,
         mod_pc,
         mod_cv,
         mod_mfa_id,
		 typ_kv_fuel_des_id,
         typ_sort
         , substr(ifnull(tyc_pcon_start, typ_pcon_start),5,2) as month_start
         , substr(ifnull(tyc_pcon_start, typ_pcon_start),1,4) as year_start
		, substr(ifnull(tyc_pcon_end, typ_pcon_end),5,2) as month_end
		, substr(ifnull(tyc_pcon_end, typ_pcon_end),1,4) as year_end
		, c.id as id_make
		, typ_mod_id as id_model
		, typ_id as id_model_detail
    from ".DB_TOF."tof__types
    inner join ".DB_TOF."tof__models on typ_mod_id = mod_id
    inner join cat as c on mod_mfa_id = c.id_tof
      ".$sJoin."
    left outer join ".DB_TOF."tof__designations model_des
                 on ".DB_TOF."tof__types.typ_kv_model_des_id = model_des.des_id 
                and model_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts mod_tex
                 on model_des.des_tex_id = mod_tex.tex_id
    left outer join ".DB_TOF."tof__designations axle_des
                 on ".DB_TOF."tof__types.typ_kv_axle_des_id = axle_des.des_id 
                and axle_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts axl_tex
                 on axle_des.des_tex_id = axl_tex.tex_id
    left outer join ".DB_TOF."tof__designations body_des
                 on ".DB_TOF."tof__types.typ_kv_body_des_id = body_des.des_id 
                and body_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts bod_tex
                 on body_des.des_tex_id = bod_tex.tex_id
    left outer join ".DB_TOF."tof__country_designations lng_des
                 on typ_mmt_cds_id = lng_des.cds_id
                and lng_des.cds_lng_id = @lng_id
                and substring(lng_des.cds_ctm,@cou_id,1) = 1
    left outer join ".DB_TOF."tof__des_texts lng_tex
                 on lng_des.cds_tex_id = lng_tex.tex_id
    left outer join ".DB_TOF."tof__country_designations uni_des
                 on typ_mmt_cds_id = uni_des.cds_id
                and uni_des.cds_lng_id = 255
                and substring(uni_des.cds_ctm,@cou_id,1) = 1
    left outer join ".DB_TOF."tof__des_texts uni_tex
                 on uni_des.cds_tex_id = uni_tex.tex_id
    left outer join ".DB_TOF."tof__typ_country_specifics
                 on typ_id = tyc_typ_id
                and tyc_cou_id = @cou_id
    left outer join ".DB_TOF."tof__designations tyc_model_des
                 on tyc_kv_model_des_id = tyc_model_des.des_id 
                and tyc_model_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts tyc_mod_tex
                 on tyc_model_des.des_tex_id = tyc_mod_tex.tex_id
    left outer join ".DB_TOF."tof__designations tyc_axle_des
                 on tyc_kv_axle_des_id = tyc_axle_des.des_id 
                and tyc_axle_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts tyc_axl_tex
                 on tyc_axle_des.des_tex_id = tyc_axl_tex.tex_id
    left outer join ".DB_TOF."tof__designations tyc_body_des
                 on tyc_kv_body_des_id = tyc_body_des.des_id 
                and tyc_body_des.des_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts tyc_bod_tex
                 on tyc_body_des.des_tex_id = tyc_bod_tex.tex_id

    left outer join ".DB_TOF."tof__country_designations short_des
	              on typ_cds_id = short_des.cds_id
		          and substring(short_des.cds_ctm,@cou_id,1) = 1
		          and short_des.cds_lng_id = @lng_id
	 left outer join ".DB_TOF."tof__des_texts short_tex
	              on short_tex.tex_id = short_des.cds_tex_id

   where 1=1 and substring(typ_ctm,@cou_id,1) = 1
    ".$sWhere;

	return $sSql;
}
?>