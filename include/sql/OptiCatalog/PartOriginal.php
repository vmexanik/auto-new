<?
function SqlOptiCatalogPartOriginalCall($aData) {
	
	if ($aData['sCode']) {
		$sWhere.=" and ctal.arl_search_number in (".$aData['sCode'].") and ctal.arl_search_number<>'' and ctal.arl_kind=1 ";
		$sJoin.=" inner join ".DB_TOF."tof__art_lookup as ctal on ctal.arl_art_id = cta.art_id ";
	} elseif ($aData['art_id']) {
		$sWhere.=" and a.ID_src in (".$aData['art_id'].")";
	} else {
		$sWhere.=" and 0=1";
	}
	
	//and ARL_BRA_ID=". Base::$aRequest['data']['id_make']

	$sSql="
	select  a.ID_src as art_id, c.Search as number, cat.title as name
	from ".DB_OCAT."cat_alt_crosses_tmp as c
	INNER JOIN ".DB_OCAT."cat_alt_articles a on a.ID_art=c.ID_art
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s on c.Brand=s.Name
	INNER JOIN cat ON cat.id_tof = s.ID_src
	 ".$sJoin."	 
	 where 1=1 AND c.Kind=3"
	.$sWhere
	;

	return $sSql;
}
?>