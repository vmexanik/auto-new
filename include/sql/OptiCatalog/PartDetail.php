<?
function SqlOptiCatalogPartDetailCall($aData) {
	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	if ($aData['id_part'] && $aData["id_model_detail"])
	{
		$sWhere.="";
	}
	else
	{
		$sWhere.=" and 0=1 ";
	}

	$sSql="select a.ID_src art_id, UPPER(a.Search) art_article_nr
			, concat(cat.pref,'_',UPPER(a.Search)) as item_code
			, a.Name as name
			, cat.title as brand
			, cat.image as image_logo
			, cat.pref as pref
			FROM ".DB_OCAT."cat_alt_link_typ_art lta
			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src = '".$aData['id_model_detail']."'
			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree = '".$aData['id_part']."' and lsg.ID_grp=lta.ID_grp
			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
			join cat on s.ID_src=cat.id_tof
		where 1=1
  ".$sWhere;

	return $sSql;
}
?>