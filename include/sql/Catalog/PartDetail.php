<?
function SqlCatalogPartDetailCall($aData) {
	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	if ($aData['id_part'] && $aData["id_model_detail"])
	{
		$sJoin.=" join ".DB_TOF."tof__link_ga_str lgs on lat_ga_id=lgs_ga_id and lgs_str_id = '".$aData['id_part']."'";
		$sWhere.=" and lat_typ_id = '".$aData['id_model_detail']."'";
	}
	else
	{
		$sWhere.=" and 0=1 ";
	}

	$sSql="select art_id, art_article_nr
			, concat(cat.pref,'_',replace(replace(replace(replace(replace(art_article_nr,' ',''),'-',''),'#',''),'.',''),'/','')) as item_code
			, tdt.tex_text as name
			, cat.title as brand
			, cat.image as image_logo
			, cat.pref as pref
			from ".DB_TOF."tof__link_la_typ_view as tlltv
            join ".DB_TOF."tof__designations as td on tlltv.ga_des_id = td.des_id and td.des_lng_id = @lng_id 
            join ".DB_TOF."tof__des_texts as tdt on td.des_tex_id=tdt.tex_id
			join cat on lat_sup_id=cat.id_tof
			".$sJoin."
		where 1=1
  ".$sWhere;

	return $sSql;
}
?>