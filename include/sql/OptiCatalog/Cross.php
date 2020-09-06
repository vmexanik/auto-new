<?
function SqlOptiCatalogCrossCall($aData) {

	if($aData['aCode'] && is_array($aData['aCode'])) {
		$aData['sCode'] = "'".implode("','",$aData['aCode'])."'";
	}

	if ($aData['code']) $aData['sCode']="'".trim($aData['code'],"'")."'";
	
	if ($aData['sCode']) {
		$sWhere.=" and a1.Search in (".$aData['sCode'].") and a1.Search<>''";
		$sWhere2.=" and a2.Search in (".$aData['sCode'].") and a2.Search<>''";
		$sWhere1.=" and cc.code in (".$aData['sCode'].") ";
	} else {
		return "select null ";
	}
	
	if ($aData['pref']) {
		
		
		$aVag=array("AU","SC","SE","VW","VAG");
		if (in_array($aData['pref'],$aVag)) {
			foreach ($aVag as $sKey => $sValue) {
			$sUnion.="
			union SELECT concat('".$sValue."','_',".$aData['sCode'].") as  item_code_crs ,  concat('".$aData['pref']."','_',".$aData['sCode'].") as item_code, 0 is_replacement, 0 as art_id, 0 as art_id2";
			}
			$sWhere.=" and cat1.pref in ('".implode("','",$aVag)."')";
			$sWhere2.=" and cat2.pref in ('".implode("','",$aVag)."')";
			$sWhere1.=" and cc.pref in ('".implode("','",$aVag)."')";
		} else {
			$sWhere.=" and cat1.pref='".$aData['pref']."'";
			$sWhere2.=" and cat2.pref='".$aData['pref']."'";
			$sWhere1.=" and cc.pref='".$aData['pref']."' ";
		}
	}

	$sWhere.= ' and a2.ID_src > 0';
	$sWhere2.=' and a1.ID_src > 0';

	$sSql="select * from (
	select concat(cat2.pref,'_',UPPER(a2.Search))  as  item_code_crs
			, concat(cat1.pref,'_',UPPER(a1.Search)) as  item_code
			, 0 as is_replacement, a1.ID_src as art_id, a2.ID_src as art_id2
	FROM ".DB_OCAT."cat_alt_crossing c
	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON c.ID_art = a1.ID_art
	INNER JOIN ".DB_OCAT."cat_alt_articles a2 ON c.ID_cross = a2.ID_art
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
	INNER JOIN cat as cat1 ON cat1.id_tof = s1.ID_src
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a2.ID_sup=s2.ID_sup
	INNER JOIN cat as cat2 ON cat2.id_tof = s2.ID_src
	where 1=1 
	".$sWhere."
	 union 
	select concat(cat1.pref,'_',UPPER(a1.Search))  as  item_code_crs
			, concat(cat2.pref,'_',UPPER(a2.Search)) as  item_code
			, 0 as is_replacement, a2.ID_src as art_id, a1.ID_src as art_id2
	FROM ".DB_OCAT."cat_alt_crossing c
	INNER JOIN ".DB_OCAT."cat_alt_articles a1 ON c.ID_art = a1.ID_art
	INNER JOIN ".DB_OCAT."cat_alt_articles a2 ON c.ID_cross = a2.ID_art
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s1 on a1.ID_sup=s1.ID_sup
	INNER JOIN cat as cat1 ON cat1.id_tof = s1.ID_src
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a2.ID_sup=s2.ID_sup
	INNER JOIN cat as cat2 ON cat2.id_tof = s2.ID_src
	where 1=1 
	".$sWhere2."
	 union 
	SELECT concat(cc.pref_crs,'_',cc.code_crs) as  item_code_crs
	   ,  concat(cc.pref,'_',cc.code) as item_code 
	   , cc.is_replacement, 0 as art_id, 0 as art_id2
	FROM cat_cross as cc 
	where 1=1 
	".$sWhere1
	.$sUnion.
	") t 
	LEFT OUTER JOIN cat_cross_stop ccs ON t.item_code=concat(ccs.pref,'_',ccs.code) and t.item_code_crs=concat(ccs.pref_crs,'_',ccs.code_crs)
	WHERE ccs.id IS null"
	;
	
	return $sSql;
}
?>