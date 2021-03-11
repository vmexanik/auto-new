<?
function SqlOptiCatalogGraphicCall($aData) {

	if(!$aData['aIdGraphic']) $aData['aIdGraphic']=array();
	$inIdGraphic = "'".implode("','",$aData['aIdGraphic'])."'";

	if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	$inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";

	//MPI-1653
	$sWhere.=" and g.path not like '0/0.jpg' ";
	
	if ($inIdGraphic and $inIdGraphic != "''" and $inIdGraphic != "'0'") {
		$sWhere.=" and a.ID_src in(".$inIdGraphic.")";
	} else {
		$sWhere.=" and 0=1 ";
	}

	if ($inIdCatPart and $inIdCatPart != "''") {
		$sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
	} else {
		$sWhere2.=" and 0=1";
	}


	if ($aData['type_image']=='pdf')
	{
		$sWhere.=" and g.path like '%pdf' ";
		$sWhere2.=" and extension='pdf' ";
	}
	else
	{
		$sWhere.=" and g.path not like '%pdf' ";
		$sWhere2.=" and extension<>'pdf' ";
	}

	$sSql="select id_cat_part as lgl_la_id,
		0 as gra_sup_id,   
        0 as gra_id,   
        0 as gra_doc_type,   
        0 as gra_lng_id,  
        0 as gra_type,   
        100 as gra_norm,   
        0 as gra_supplier_nr,
		0 as gra_tab_nr, 
        0 as  gra_grd_id,
		concat( '".Base::getConstant('global:project_url','http://irbis.mstarproject.com')."',image) as img_path
        , width as img_width
        , cp.id as id_cat_pic
FROM cat_pic cp
where 1=1 
".$sWhere2."
UNION
select a.ID_src,
a.ID_sup,
         1 gra_id,   
         3 gra_doc_type,   
         0 gra_lng_id,   
         0 gra_type,   
         100 gra_norm,   
		 1 gra_supplier_nr,
         1 gra_tab_nr,
         1 gra_grd_id ,
		 concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , g.path) as img_path 
         , 100 as img_width
         , 0 as id_cat_pic       
FROM ".DB_OCAT."cat_alt_images g
JOIN ".DB_OCAT."cat_alt_articles a ON a.ID_art = g.ID_art
where 1=1 
".$sWhere;

	return $sSql;
}
?>