<?
function SqlCatalogGraphicCall($aData) {

	if(!$aData['aIdGraphic']) $aData['aIdGraphic']=array();
	$inIdGraphic = "'".implode("','",$aData['aIdGraphic'])."'";

	if(!$aData['aIdCatPart']) $aData['aIdCatPart']=array();
	$inIdCatPart = "'".implode("','",$aData['aIdCatPart'])."'";

	if ($inIdGraphic) {
		$sWhere.=" and lga_art_id in(".$inIdGraphic.")";
		$sWhere1.=" and lgl_la_id in(".$inIdGraphic.")";
	} else {
		$sWhere.=" and 0=1 ";
		$sWhere1.=" and 0=1 ";
	}

	if ($inIdCatPart) {
		$sWhere2.=" and id_cat_part in(".$inIdCatPart.")";
	} else {
		$sWhere2.=" and 0=1";
	}


	if ($aData['type_image']=='pdf')
	{
		$sWhere.=" and gra_doc_type=2 ";
		$sWhere1.=" and gra_doc_type=2 ";
		$sWhere2.=" and extension='pdf' ";
	}
	else
	{
		$sWhere.=" and gra_doc_type<>2 ";
		$sWhere1.=" and gra_doc_type<>2 ";
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
        image as img_path
        , width as img_width
        , cp.id as id_cat_pic
FROM cat_pic cp
where 1=1 
".$sWhere2."
UNION
select lga_art_id,
gra_sup_id,
         gra_id,   
         gra_doc_type,   
         gra_lng_id,   
         gra_type,   
         gra_norm,   
		 gra_supplier_nr,
         gra_tab_nr,
         gra_grd_id ,
		 concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , case gra_doc_type when 2 then 'pdf' else gra_tab_nr end 
         , '/', case gra_doc_type when 2 then concat(gra_id, lpad(gra_lng_id, 3, '0')) else gra_grd_id end
         , case gra_doc_type when 1 then '.bmp' when 2 then '.pdf' when 3 then '.jpg' when 4 then '.jpg' when 5 then '.png' end         
         ) as img_path 
         , gra_norm as img_width
         , 0 as id_cat_pic       
FROM ".DB_TOF."tof__graphics g
JOIN ".DB_TOF."tof__link_gra_art ON gra_id = lga_gra_id 
where 1=1 
".$sWhere."
 UNION
SELECT lgl_la_id,
		gra_sup_id,   
         gra_id,   
         gra_doc_type,   
         gra_lng_id,  
         gra_type,   
         gra_norm,   
         gra_supplier_nr,
		 gra_tab_nr, 
         gra_grd_id,
         concat( '/imgbank/tcd/' , case gra_doc_type when 2 then 'pdf' else gra_tab_nr end 
         , '/', case gra_doc_type when 2 then concat(gra_id, lpad(gra_lng_id, 3, '0')) else gra_grd_id end
         , case gra_doc_type when 1 then '.bmp' when 2 then '.pdf' when 3 then '.jpg' when 4 then '.jpg' when 5 then '.png' end         
         ) as img_path
         , gra_norm as img_width
         , 0 as id_cat_pic
FROM ".DB_TOF."tof__graphics g
JOIN ".DB_TOF."tof__link_gra_la ON gra_id = lgl_gra_id
where 1=1 
".$sWhere1;

	return $sSql;
}
?>