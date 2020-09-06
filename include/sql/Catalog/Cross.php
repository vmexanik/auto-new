<?
function SqlCatalogCrossCall($aData) {

	if($aData['aCode'] && is_array($aData['aCode'])) {
		$aData['sCode'] = "'".implode("','",$aData['aCode'])."'";
	}

	if ($aData['code']) $aData['sCode']="'".trim($aData['code'],"'")."'";
	
	if ($aData['sCode']) {
		$sWhere.=" and ctal.ARL_SEARCH_NUMBER in (".$aData['sCode'].") and ctal.ARL_SEARCH_NUMBER<>''";
		$sWhere1.=" and cc.code in (".$aData['sCode'].") ";
	} else {
		return "select null ";
	}
	
	if ($aData['pref']) {
		
		
		$aVag=array("AU","SC","SE","VW","VAG");
		if (in_array($aData['pref'],$aVag)) {
			foreach ($aVag as $sKey => $sValue) {
			$sUnion.="
			union SELECT concat('".$sValue."','_',".$aData['sCode'].") as  item_code_crs ,  concat('".$aData['pref']."','_',".$aData['sCode'].") as item_code, 0 is_replacement, 0 as art_id";
			}
			$sWhere.=" and cat1.pref in ('".implode("','",$aVag)."')";
			$sWhere1.=" and cc.pref in ('".implode("','",$aVag)."')";
		} else {
			$sWhere.=" and cat1.pref='".$aData['pref']."'";
			$sWhere1.=" and cc.pref='".$aData['pref']."' ";
		}
	}

	//concat(cat1.pref,'_',replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(cta.ART_ARTICLE_NR),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')',''))  as  item_code_crs
	$aArtId=Db::GetAssoc("
	select  cta.art_id as id, cta.art_id as art_id
	FROM ".DB_TOF."tof__articles as cta
	INNER JOIN ".DB_TOF."tof__art_lookup as ctal ON ctal.ARL_ART_ID = cta.ART_ID
	INNER JOIN cat as cat1 ON cat1.id_tof = cta.ART_SUP_ID
	where 1=1 and ctal.ARL_KIND in ('1','2','5') 
	".$sWhere
	);
	
	if ($aArtId) {
		$inArtId=implode(",",$aArtId);
		$sWhere2=" and cta.art_id in (".$inArtId.")";
	} else {
		$sWhere2=" and 0=1";
	}
			
	$sSql="select * from (
	select concat(cat2.pref,'_',replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(cta.ART_ARTICLE_NR),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')',''))  as  item_code_crs
				, concat(cat1.pref,'_',replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(ctal.ARL_SEARCH_NUMBER),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')','')) as  item_code
	 			, ctal.ARL_KIND as is_replacement, cta.art_id as art_id
	FROM ".DB_TOF."tof__articles as cta
	INNER JOIN ".DB_TOF."tof__art_lookup as ctal ON ctal.ARL_ART_ID = cta.ART_ID
	INNER JOIN cat as cat1 ON cat1.id_tof = ctal.ARL_BRA_ID
	INNER JOIN cat as cat2 ON cat2.id_tof = cta.ART_SUP_ID
	where 1=1 and ctal.ARL_KIND in ('3','4')
	".$sWhere."
	 union 
	select  concat(cat2.pref,'_',replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(ctal.ARL_SEARCH_NUMBER),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')','')) as  item_code_crs
			, concat(cat2.pref,'_',replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(cta.ART_ARTICLE_NR),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')',''))  as  item_code
		    , ctal.ARL_KIND as is_replacement, cta.art_id as art_id
	FROM ".DB_TOF."tof__articles as cta
	INNER JOIN ".DB_TOF."tof__art_lookup as ctal ON ctal.ARL_ART_ID = cta.ART_ID
	INNER JOIN cat as cat2 ON cat2.id_tof = cta.ART_SUP_ID
	where 1=1 and ctal.ARL_KIND in ('1','2','5')
	".$sWhere2."
	 union 
	SELECT concat(cc.pref_crs,'_',cc.code_crs) as  item_code_crs
	   ,  concat(cc.pref,'_',cc.code) as item_code 
	   , cc.is_replacement, 0 as art_id
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