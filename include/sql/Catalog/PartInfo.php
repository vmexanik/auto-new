<?
function SqlCatalogPartInfoCall($aData) {
	
	if ($aData['item_code']) {
		list($aData['pref'],$aData['sCode'])=explode("_",$aData['item_code']);
	}
	
	if ($aData['sCode']) {
		$sWhere.=" and ctal.arl_search_number in ('".$aData['sCode']."') and ctal.arl_search_number<>'' and ctal.arl_kind=1 ";
		$sJoin.=" inner join ".DB_TOF."tof__art_lookup as ctal on ctal.arl_art_id = cta.art_id ";
		$sWhere1.=" and cp.code in ('".$aData['sCode']."')";
	} elseif ($aData['art_id']) {
		$sWhere.=" and cta.art_id in (".$aData['art_id'].")";
		$sWhere1.=" and 0=1"; 
	} elseif ($aData['id_cat_part']) {
		$sWhere.=" and 0=1"; 
		$sWhere1.=" and cp.id in (".$aData['id_cat_part'].")";
	} else {
		return "select null ";
	}
	
	if ($aData['pref']) {
		$sWhere.=" and cat2.pref='".$aData['pref']."'";
		$sWhere1.=" and cp.pref='".$aData['pref']."'";
	}

	$sSql="
	select  cta.art_id, cta.art_article_nr as code
 			, concat(cat2.pref,'_',replace(replace(replace(replace(replace(cta.art_article_nr,' ',''),'-',''),'#',''),'.',''),'/','')) as  item_code
 			, cat2.pref, cat2.title as brand, dest.tex_text as name
 			, ifnull(
 					(Select id 
 					 from cat_part 
 					 where item_code=concat(cat2.pref,'_',replace(replace(replace(replace(replace(cta.art_article_nr,' ',''),'-',''),'#',''),'.',''),'/','')))
 					, 0) as id_cat_part
	 from ".DB_TOF."tof__articles as cta
	 inner join ".DB_TOF."tof__designations as des on cta.art_complete_des_id = des.des_id  and des.des_lng_id = @lng_id 
	 inner join ".DB_TOF."tof__des_texts dest on des.des_tex_id=dest.tex_id
	 inner join cat as cat2 on cat2.id_tof = cta.art_sup_id
	 ".$sJoin."	 
	 where 1=1 
	 ".$sWhere.
	" union all
	Select '0', cp.code as code
 	, cp.item_code as  item_code
 	, cp.pref as pref, c.title as brand, cp.name_rus as name
 	, cp.id as id_cat_part
 	from cat_part as cp 
	inner join cat as c on cp.pref = c.pref
	where 1=1 
	".$sWhere1
	;
	
	return $sSql;
}
?>