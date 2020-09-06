<?
function SqlOptiCatalogPartCriteriaCall($aData) {

	if ($aData['where'])
	{
		$sWhere=$aData['where'];
	}

	if(!$aData['aId']) $aData['aId']=array();
	$inId = "'".implode("','",$aData['aId'])."'";
	$inIdOpti = "'".Db::GetOne("select group_concat(ID_art SEPARATOR '\',\'') from ".DB_OCAT."cat_alt_articles 
		where ID_src>0 and ID_src in(".$inId.")")."'";

	if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	$inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";


	if ($inIdOpti) {
		$sWhere.=" and art.ID_art in(".$inIdOpti.")";
		if ($aData['id_model_detail']) {
			$sWhere1.=" and art.ID_art in(".$inIdOpti.") and t.ID_src=".$aData['id_model_detail'];
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
		$sGroup.=" group by crt.acr_art_id";
		
		if ($aData['type_']=="only_la") $sWhere.=" and 0=1 ";
	}

	$sSql="
	select 
	".$sField."
	from (
	select art.ID_src as acr_art_id
		, a.Name as krit_name
		, a.Value as krit_value
		, 2 flag
		, lac.Sort sort
		, 0 as id_cat_info
   from ".DB_OCAT."cat_alt_additions a
		join ".DB_OCAT."cat_alt_link_art_inf lac on lac.ID_add=a.ID_add
		join ".DB_OCAT."cat_alt_articles art on art.ID_art=lac.ID_art
where 1=1 
  ".$sWhere."
  union all
select art.ID_src as acr_art_id	
		, a.Name as krit_name
		, a.Value as krit_value
	   , 1 flag
	   , ltc.Sort sort
	   , 0 as id_cat_info
   from ".DB_OCAT."cat_alt_additions a
		join ".DB_OCAT."cat_alt_link_typ_inf ltc on ltc.ID_add=a.ID_add
		join ".DB_OCAT."cat_alt_articles art on art.ID_art=ltc.ID_art
		join ".DB_OCAT."cat_alt_types t on t.ID_typ=ltc.ID_typ
	where  1=1 
  ".$sWhere1."
  union all
	select id_cat_part as acr_art_id
		, name as krit_name
		, code as krit_value
		, 2 as flag
		, 0 as sort
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