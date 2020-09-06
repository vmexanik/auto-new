<?
function SqlCatalogAssemblageCall($aData) {

	//	Db::Execute(" create temporary table t_str_id
	//		select distinct(lgs_str_id) as t_str_id
	//		from ".DB_TOF."tof__link_ga_str
	//		join ".DB_TOF."tof__link_la_typ
	//		on lat_typ_id = 22469 and lgs_ga_id=lat_ga_id and substr(lat_ctm,185,1)=1
	//	");
	if (!$aData['id_model_detail']) return "select null";

	$sSql="select str_id as id,
       str_level,
       str_sort,
       0 expand,
       tex_text as data,
       str_id_parent,
       0 color
  from ".DB_TOF."tof__search_tree
  join ".DB_TOF."tof__designations  on str_des_id=des_id and  des_lng_id = @lng_id
  join ".DB_TOF."tof__des_texts on des_tex_id=tex_id
  /*join (select distinct(lgs_str_id) as t_str_id
		from ".DB_TOF."tof__link_ga_str
		join ".DB_TOF."tof__link_la_typ
		on lat_typ_id = ".$aData['id_model_detail']." and lgs_ga_id=lat_ga_id) as t on str_id=t_str_id*/
  join ".DB_TOF."tof__link_typ_str on lts_typ_id = ".$aData['id_model_detail']." and str_id=lts_str_id
   where 1 = 1 and  str_type = 1 and   str_level > 1 
   ".$sWhere
	." order by data";
	return $sSql;
}
// for full table ".DB_TOF."tof__link_la_typ
// and substr(lat_ctm,185,1)=1
?>