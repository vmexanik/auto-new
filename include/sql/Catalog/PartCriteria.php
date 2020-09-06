<?
function SqlCatalogPartCriteriaCall($aData) {

	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	if(!$aData['aId']) $aData['aId']=array();
	$inId = "'".implode("','",$aData['aId'])."'";

	if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	$inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";


	if ($inId) {
		$sWhere.=" and acr_art_id in(".$inId.")";
		if ($aData['id_model_detail']) {
			$sWhere1.=" and la_art_id in(".$inId.") and lat_typ_id=".$aData['id_model_detail'];
		} else {
			$sWhere1.=" and 0=1 ";
		}
	} else {
		$sWhere.=" and 0=1 ";
		$sWhere1.=" and 0=1 ";
	}

	if ($inIdCatPart)
	{
		$sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
	}
	else
	{
		$sWhere2.=" and 0=1 ";
	}

	if ($aData['type_']=="all") {
		$sField.=" distinct  krit_name, krit_value";
	} elseif ($aData['type_']=="all_edit") {
		$sField.=" krit_name, krit_value, id_cat_info";
	} else {
		$sField.=" group_concat(' ', krit_name, ' ', krit_value) as criteria ";
		$sGroup.=" group by acr_art_id";
		
		if ($aData['type_']=="only_la") $sWhere.=" and 0=1 ";
	}

	$sSql="
	select 
	".$sField."
	from (
	select acr_art_id
		, des_texts.tex_text as krit_name
		, ifnull(des_texts2.tex_text, acr_value) as krit_value
		, 2 flag
		, acr_sort sort
		, acr_kv_des_id kv_des_id
		, acr_cri_id cri_id
		, acr_ga_id ga_id
		, 0 as id_cat_info
   from
		".DB_TOF."tof__article_criteria
	left join ".DB_TOF."tof__designations as designations2 on designations2.des_id = acr_kv_des_id
	left join ".DB_TOF."tof__des_texts as des_texts2 on des_texts2.tex_id = designations2.des_tex_id
	inner join ".DB_TOF."tof__criteria on cri_id = acr_cri_id
	inner join ".DB_TOF."tof__designations as designations on designations.des_id = cri_des_id
	inner join ".DB_TOF."tof__des_texts as des_texts on des_texts.tex_id = designations.des_tex_id
where 1=1 and (designations.des_lng_id is null or designations.des_lng_id = @lng_id) and (designations2.des_lng_id is null or designations2.des_lng_id = @lng_id)
  ".$sWhere."
  union all
select la_art_id		
	   , trim(cri_tex.tex_text) krit_name
	   , coalesce(lac_value, lac_tex.tex_text) krit_value
	   , 1 flag
	   , lac_sort sort
	   , lac_kv_des_id kv_des_id
	   , lac_cri_id cri_id
	   , la_ga_id ga_id
	   , 0 as id_cat_info
	from  ".DB_TOF."tof__la_criteria  
	join ".DB_TOF."tof__link_art on la_id = lac_la_id
	join ".DB_TOF."tof__link_la_typ on lat_la_id =la_id and lat_ga_id=la_ga_id
	join ".DB_TOF."tof__criteria on cri_id = lac_cri_id 
	join ".DB_TOF."tof__designations cri_des on cri_des.des_id = cri_des_id and cri_des.des_lng_id = @lng_id 
	join ".DB_TOF."tof__des_texts cri_tex on cri_tex.tex_id = cri_des.des_tex_id
	left join ".DB_TOF."tof__designations lac_des on lac_des.des_id = ifnull(lac_kv_des_id,-1) and lac_des.des_lng_id = @lng_id 
	left join ".DB_TOF."tof__des_texts lac_tex on lac_tex.tex_id = lac_des.des_tex_id
	where  1=1 
  ".$sWhere1."
  union all
	select id_cat_part as acr_art_id
		, name as krit_name
		, code as krit_value
		, 2 as flag
		, 0 as sort
		, 0 as kv_des_id
		, 0 as cri_id
		, 0 as ga_id
		, id as id_cat_info
   from cat_info				
	where 1=1 
 ".$sWhere2."
) as crt
".$sGroup;
	
	//Debug::PrintPre($sSql);

	return $sSql;
}
?>