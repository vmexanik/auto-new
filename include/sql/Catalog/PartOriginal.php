<?
function SqlCatalogPartOriginalCall($aData) {
	
	if ($aData['sCode']) {
		$sWhere.=" and ctal.arl_search_number in (".$aData['sCode'].") and ctal.arl_search_number<>'' and ctal.arl_kind=1 ";
		$sJoin.=" inner join ".DB_TOF."tof__art_lookup as ctal on ctal.arl_art_id = cta.art_id ";
	} elseif ($aData['art_id']) {
		$sWhere.=" and talk.arl_art_id in (".$aData['art_id'].")";
		//where `ARL_ART_ID` in (".implode(",",$aId).")
	} else {
		$sWhere.=" and 0=1";
	}
	
	//and ARL_BRA_ID=". Base::$aRequest['data']['id_make']

	$sSql="
	select  talk.arl_art_id as art_id, talk.arl_search_number as number, c.title as name
	from ".DB_TOF."tof__art_lookup_kind3 as talk
	inner join cat as c on talk.arl_bra_id=c.id_tof
	 ".$sJoin."	 
	 where 1=1 "
	.$sWhere
	;

	return $sSql;
}
?>