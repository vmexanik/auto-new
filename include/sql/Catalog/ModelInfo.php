<?
function SqlCatalogModelInfoCall($aData) {
	
	if ($aData['id_model_detail']) 
	{
		$sWhere.=" and ".DB_TOF."tof__types.typ_id = ".$aData['id_model_detail'];
	}
	else 
	{
		$sWhere=" and 1=0";
	}
	
	$sSql="select 
		 -- ".DB_TOF."tof__types.typ_id as id_type_tecdoc,
         
		 coalesce(lng_tex.tex_text, uni_tex.tex_text) type_auto,
         concat(substr(coalesce(specifics.tyc_pcon_start, ".DB_TOF."tof__types.typ_pcon_start),5,2),
         '.', substr(coalesce(specifics.tyc_pcon_start, ".DB_TOF."tof__types.typ_pcon_start),1,4)) model_year_from,
         concat(substr(coalesce(specifics.tyc_pcon_end, ".DB_TOF."tof__types.typ_pcon_end),5,2),
         '.', substr(coalesce(specifics.tyc_pcon_end, ".DB_TOF."tof__types.typ_pcon_end),1,4)) model_year_to,
         -- coalesce(specifics.tyc_pcon_start, ".DB_TOF."tof__types.typ_pcon_start) model_year_from_,   
         -- coalesce(specifics.tyc_pcon_end, ".DB_TOF."tof__types.typ_pcon_end) model_year_to,   
         
         coalesce(specifics.tyc_kw_from, ".DB_TOF."tof__types.typ_kw_from) pover_output_kw,   
         coalesce(specifics.tyc_hp_from, ".DB_TOF."tof__types.typ_hp_from) power_output_hp,     
         coalesce(specifics.tyc_kw_upto, ".DB_TOF."tof__types.typ_kw_upto) power_to_kw,     
         coalesce(specifics.tyc_hp_upto, ".DB_TOF."tof__types.typ_hp_upto) power_to_hp,     
         ".DB_TOF."tof__types.typ_ccm as tech_engine_capacity,   
         des_text_b.tex_text body_type,   
         des_text_d.tex_text drive_type,   
         des_text_e.tex_text fuel_type,   
         coalesce(des_text_fcou.tex_text, des_text_f.tex_text) brake_system,
         coalesce(des_text_gcou.tex_text, des_text_g.tex_text) brake,   
         coalesce(des_text_hcou.tex_text, des_text_h.tex_text) abs_des,   
         coalesce(des_text_icou.tex_text, des_text_i.tex_text) asr,   
         coalesce(des_text_jcou.tex_text, des_text_j.tex_text) katart,   
         des_text_k.tex_text steering_gear,   
         des_text_l.tex_text lenkart,   
         coalesce(des_text_mcou.tex_text, des_text_m.tex_text) spannung,   
         des_text_n.tex_text engine_type,   
         coalesce(des_text_ocou.tex_text, des_text_o.tex_text) gearbox,  
         -- ".DB_TOF."tof__types.typ_max_weight,   
         -- ".DB_TOF."tof__types.typ_kv_body_des_id,   
         -- ".DB_TOF."tof__types.typ_kv_engine_des_id,   
         -- ".DB_TOF."tof__types.typ_kv_axle_des_id,   
         -- ".DB_TOF."tof__types.typ_kv_drive_des_id, 
         -- ".DB_TOF."tof__types.typ_mod_id,   
         -- ".DB_TOF."tof__types.typ_id,   
         coalesce(specifics.tyc_tank, ".DB_TOF."tof__types.typ_tank) tank,
			coalesce(specifics.tyc_doors, ".DB_TOF."tof__types.typ_doors) door,
			".DB_TOF."tof__types.typ_max_weight,
         texts_fuel_supply.tex_text fuel_supply,
         coalesce(specifics.tyc_cylinders, ".DB_TOF."tof__types.typ_cylinders) cylinder,
         ".DB_TOF."tof__types.typ_valves
    from ".DB_TOF."tof__types 
         left outer join ".DB_TOF."tof__designations designations_b 
                      on ".DB_TOF."tof__types.typ_kv_body_des_id = designations_b.des_id 
                     and designations_b.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_b 
                      on designations_b.des_tex_id = des_text_b.tex_id

         left outer join ".DB_TOF."tof__designations designations_d 
                      on ".DB_TOF."tof__types.typ_kv_drive_des_id = designations_d.des_id 
                     and designations_d.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_d 
                      on designations_d.des_tex_id = des_text_d.tex_id
         left outer join ".DB_TOF."tof__designations designations_e 
                      on ".DB_TOF."tof__types.typ_kv_fuel_des_id = designations_e.des_id 
                     and designations_e.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_e 
                      on designations_e.des_tex_id = des_text_e.tex_id
         left outer join ".DB_TOF."tof__designations designations_f 
                      on ".DB_TOF."tof__types.typ_kv_brake_syst_des_id = designations_f.des_id 
                     and designations_f.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_f 
                      on designations_f.des_tex_id = des_text_f.tex_id
         left outer join ".DB_TOF."tof__designations designations_g 
                      on ".DB_TOF."tof__types.typ_kv_brake_type_des_id = designations_g.des_id 
                     and designations_g.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_g 
                      on designations_g.des_tex_id = des_text_g.tex_id
         left outer join ".DB_TOF."tof__designations designations_h
                      on ".DB_TOF."tof__types.typ_kv_abs_des_id = designations_h.des_id 
                     and designations_h.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_h 
                      on designations_h.des_tex_id = des_text_h.tex_id
         left outer join ".DB_TOF."tof__designations designations_i
                      on ".DB_TOF."tof__types.typ_kv_asr_des_id = designations_i.des_id 
                     and designations_i.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_i 
                      on designations_i.des_tex_id = des_text_i.tex_id
         left outer join ".DB_TOF."tof__designations designations_j
                      on ".DB_TOF."tof__types.typ_kv_catalyst_des_id = designations_j.des_id 
                     and designations_j.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_j 
                      on designations_j.des_tex_id = des_text_j.tex_id
         left outer join ".DB_TOF."tof__designations designations_k
                      on ".DB_TOF."tof__types.typ_kv_steering_des_id = designations_k.des_id 
                     and designations_k.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_k 
                      on designations_k.des_tex_id = des_text_k.tex_id
			left outer join ".DB_TOF."tof__designations designations_l
                      on ".DB_TOF."tof__types.typ_kv_steering_side_des_id = designations_l.des_id 
                     and designations_l.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_l 
                      on designations_l.des_tex_id = des_text_l.tex_id
         left outer join ".DB_TOF."tof__designations designations_m
                      on ".DB_TOF."tof__types.typ_kv_voltage_des_id = designations_m.des_id 
                     and designations_m.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_m 
                      on designations_m.des_tex_id = des_text_m.tex_id  
         left outer join ".DB_TOF."tof__designations designations_n
                      on ".DB_TOF."tof__types.typ_kv_engine_des_id = designations_n.des_id 
                     and designations_n.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_n 
                      on designations_n.des_tex_id = des_text_n.tex_id  
         left outer join ".DB_TOF."tof__designations designations_o
                      on ".DB_TOF."tof__types.typ_kv_trans_des_id = designations_o.des_id 
                     and designations_o.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_o 
                      on designations_o.des_tex_id = des_text_o.tex_id  
        left outer join ".DB_TOF."tof__designations des_fuel_supply
                      on ".DB_TOF."tof__types.typ_kv_fuel_supply_des_id = des_fuel_supply.des_id 
                     and des_fuel_supply.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts texts_fuel_supply
                      on des_fuel_supply.des_tex_id = texts_fuel_supply.tex_id
			left outer join ".DB_TOF."tof__typ_country_specifics specifics 
                      on ".DB_TOF."tof__types.typ_id = specifics.tyc_typ_id
						   and specifics.tyc_cou_id = 185
			left outer join ".DB_TOF."tof__designations designations_fcou 
                      on specifics.tyc_kv_brake_syst_des_id = designations_fcou.des_id 
                     and designations_fcou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_fcou 
                      on designations_fcou.des_tex_id = des_text_fcou.tex_id
         left outer join ".DB_TOF."tof__designations designations_gcou 
                      on specifics.tyc_kv_brake_type_des_id = designations_gcou.des_id 
                     and designations_gcou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_gcou 
                      on designations_gcou.des_tex_id = des_text_gcou.tex_id
         left outer join ".DB_TOF."tof__designations designations_hcou
                      on specifics.tyc_kv_abs_des_id = designations_hcou.des_id 
                     and designations_hcou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_hcou 
                      on designations_hcou.des_tex_id = des_text_hcou.tex_id
         left outer join ".DB_TOF."tof__designations designations_icou
                      on specifics.tyc_kv_asr_des_id = designations_icou.des_id 
                     and designations_icou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_icou 
                      on designations_icou.des_tex_id = des_text_icou.tex_id
         left outer join ".DB_TOF."tof__designations designations_jcou
                      on specifics.tyc_kv_catalyst_des_id = designations_jcou.des_id 
                     and designations_jcou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_jcou 
                      on designations_jcou.des_tex_id = des_text_jcou.tex_id
         left outer join ".DB_TOF."tof__designations designations_mcou
                      on specifics.tyc_kv_voltage_des_id = designations_mcou.des_id 
                     and designations_mcou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_mcou 
                      on designations_mcou.des_tex_id = des_text_mcou.tex_id  
         left outer join ".DB_TOF."tof__designations designations_ocou
                      on specifics.tyc_kv_trans_des_id = designations_ocou.des_id 
                     and designations_ocou.des_lng_id = @lng_id 
         left outer join ".DB_TOF."tof__des_texts des_text_ocou 
                      on designations_ocou.des_tex_id = des_text_ocou.tex_id  
    left outer join ".DB_TOF."tof__country_designations lng_des
                 on typ_mmt_cds_id = lng_des.cds_id
                and lng_des.cds_lng_id = @lng_id
    left outer join ".DB_TOF."tof__des_texts lng_tex
                 on lng_des.cds_tex_id = lng_tex.tex_id
    left outer join ".DB_TOF."tof__country_designations uni_des
                 on typ_mmt_cds_id = uni_des.cds_id
                and uni_des.cds_lng_id = 255
    left outer join ".DB_TOF."tof__des_texts uni_tex
                 on uni_des.cds_tex_id = uni_tex.tex_id
   where 1=1 
   ".$sWhere;

	return $sSql;
}
?>