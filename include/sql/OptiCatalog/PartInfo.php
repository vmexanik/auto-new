<?
function SqlOptiCatalogPartInfoCall($aData) {
	
	if ($aData['item_code']) {
		list($aData['pref'],$aData['sCode'])=explode("_",$aData['item_code']);
	}
	
	if ($aData['sCode']) {
		$sWhere.=" and a.Search in ('".$aData['sCode']."') and a.Search<>'' ";
		$sWhere1.=" and cp.code in ('".$aData['sCode']."')";
	} elseif ($aData['art_id']) {
		$sWhere.=" and a.ID_src in (".$aData['art_id'].")";
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
	select  a.ID_src art_id, a.Search as code
 			, concat(cat2.pref,'_',a.Search) as  item_code
 			, cat2.pref, cat2.title as brand, a.Name as name
 			, ifnull(
 					(Select id 
 					 from cat_part 
 					 where item_code=concat(cat2.pref,'_',a.Search))
 					, 0) as id_cat_part
	,cat2.name as cat_name
	 from ".DB_OCAT."cat_alt_articles as a
	INNER JOIN ".DB_OCAT."cat_alt_suppliers as s2 on a.ID_sup=s2.ID_sup
	INNER JOIN cat as cat2 ON cat2.id_tof = s2.ID_src
	 ".$sJoin."	 
	 where 1=1 
	 ".$sWhere.
	" union all
	Select '0', cp.code as code
 	, cp.item_code as  item_code
 	, cp.pref as pref, c.title as brand, cp.name_rus as name
 	, cp.id as id_cat_part
	,c.name as cat_name
 	from cat_part as cp 
	inner join cat as c on cp.pref = c.pref
	where 1=1 
	".$sWhere1
	;
	
	return $sSql;
}
?>