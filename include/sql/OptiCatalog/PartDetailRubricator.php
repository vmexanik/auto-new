<?
function SqlOptiCatalogPartDetailRubricatorCall($aData) {
	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	$sJoin="inner join filter_optim as f on f.art_id=lta.ID_art";
	if (!$aData['id_part'])
		$sWhere.=" and 0=1 ";
	$sGroup = " group by f.art_id ";
	
	if ($aData['id_tof'])
		$sWhere .= " and mfa.ID_src='".$aData['id_tof']."' ";
	
	if ($aData['id_model'])
		$sWhere .= " and m.ID_src='".$aData['id_model']."' ";
	
	$sSql = "select f.art_id, UPPER(a.Search) art_article_nr
			, concat(cat.pref,'_',UPPER(a.Search)) as item_code
			, a.Name as name
			, cat.title as brand
			, cat.image as image_logo
			, cat.pref as pref 
			FROM ".DB_OCAT."cat_alt_link_str_art lsg use index (id_tree)
			INNER JOIN ".DB_OCAT."cat_alt_articles a ON a.ID_art = lsg.ID_art
			INNER JOIN ".DB_OCAT."cat_alt_link_typ_art lta ON lta.ID_art=lsg.ID_art
			INNER JOIN ".DB_OCAT."cat_alt_types t ON lta.ID_typ=t.ID_typ
			INNER JOIN ".DB_OCAT."cat_alt_models m ON m.ID_mod=t.ID_mod
			INNER JOIN ".DB_OCAT."cat_alt_manufacturer mfa ON m.ID_mfa=mfa.ID_mfa
			INNER JOIN cat ON cat.id_tof=lsg.id_sup_src and cat.visible=1
			inner join filter_optim as f on f.art_id=a.ID_src
			where 1=1 and lsg.id_tree in ('".$aData['id_part']."')".$sWhere.$sGroup;
	
	return $sSql;
}
?>