<?
function SqlOptiCatalogModelDetailCall($aData) {
	
	if ($aData['id_model']) 
	{
		$sWhere.="and cat_alt_models.ID_src = ".$aData['id_model'];
	}
	elseif ($aData["type_number"]) 
	{
		$sJoin=" inner join ".DB_TOF."tof__type_numbers as ttn on ".DB_TOF."tof__types.typ_id = ttn.tyn_typ_id ";
		$sWhere.=" and ttn.tyn_kind = 2  AND ttn.tyn_search_text like '".$aData["type_number"]."%' ";
	}
	elseif ($aData["code"] && isset($aData["art_id"])) 
	{
		$aTypId=array();
		if(!$aData["art_id"]) $aData["art_id"]=-1;
		
		if($aData["art_id"] != -1)
			$aTmp=Db::GetAll("select cat_alt_types.ID_src
			from ".DB_OCAT."cat_alt_types
				inner join ".DB_OCAT."cat_alt_link_typ_art lta on lta.ID_typ = cat_alt_types.ID_typ
				inner join ".DB_OCAT."cat_alt_articles a on a.id_art=lta.ID_art 
					and a.ID_src='".$aData["art_id"]."' and a.Search='".$aData["code"]."'"
			);

		if ($aTmp) foreach ($aTmp as $aValue) {
			$aTypId[]=$aValue['ID_src'];
		}

		if ($aData['id_cat_part']) {
			$aTmp=Db::GetAll($s="select id_cat_model_type
			from cat_model_type_link
			where id_cat_part = '".$aData["id_cat_part"]."'"
			);
			if ($aTmp) foreach ($aTmp as $aValue) {
				$aTypId[]=$aValue['id_cat_model_type'];
			}
		}

		//$sJoin=" inner join ".DB_TOF."tof__link_la_typ_view on typ_id = lat_typ_id";
		if ($aTypId != array())
			$sWhere.=" and cat_alt_types.ID_src in (".implode(",",$aTypId).")";
		else 
			$sWhere.=" and 0=1";

		/*$sJoin=" inner join ".DB_OCAT."cat_alt_link_typ_art lta on lta.ID_typ = cat_alt_types.ID_typ
			inner join ".DB_OCAT."cat_alt_articles a on a.id_art=lta.ID_art 
				and a.ID_src='".$aData["art_id"]."' and a.Search='".$aData["code"]."'";*/
	}
	else 
	{
		$sWhere="and 1=0";
	}
	
	if ($aData['id_model_detail']) 
	{
		$sWhere.=" and cat_alt_types.ID_src = ".$aData['id_model_detail'];
	}
	
	if ($aData['year']) 
	{
		$sWhere.=" and ifnull(tyc_pcon_start, typ_pcon_start)<=".$aData['year']."01"
		." and ifnull(ifnull(tyc_pcon_end, typ_pcon_end),999999)>=".$aData['year']."01";
	}
	
	
	if ($aData['volume']) 
	{
		$dVolume=str_replace(",",".",$aData['volume']);
		if ($dVolume<=100) {
			$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
			.($dVolume*1000);
		} else {
			$sWhere.=" and round(ifnull(tyc_ccm, typ_ccm),-2)="
			.round($dVolume,-2);
		}
		
		//$sWhere.=" and substring(ifnull(tyc_ccm, typ_ccm),1,2)="
		//.substr(str_replace(array(",","."),"",$aData['volume']),0,2);
	}
	
	if ($aData['fuel']) 
	{
		$sWhere.=" and ifnull(tyc_kv_engine_des_id, typ_kv_engine_des_id)=".$aData['fuel'];
	}
		
		
	$sSql="select cat_alt_types.*
         , substr(cat_alt_types.DateStart,5,2) as month_start
         , substr(cat_alt_types.DateStart,1,4) as year_start
		, substr(cat_alt_types.DateEnd,5,2) as month_end
		, substr(cat_alt_types.DateEnd,1,4) as year_end
		, c.id as id_make
		, cat_alt_models.ID_src as id_model
		, cat_alt_types.ID_src as id_model_detail
		, cat_alt_types.Description as name
		, LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
		, SUBSTR(KwHp, LOCATE('/', KwHp)+1) hp_from
		, CCM as ccm, Body as body
		, cat_alt_models.ID_src as mod_id
		, cat_alt_types.ID_src as typ_id
		, cat_alt_manufacturer.ID_src as mod_mfa_id
    FROM ".DB_OCAT."cat_alt_types
    inner join ".DB_OCAT."cat_alt_models on cat_alt_models.ID_mod = cat_alt_types.ID_mod
	inner join ".DB_OCAT."cat_alt_manufacturer on cat_alt_models.ID_mfa=cat_alt_manufacturer.ID_mfa
    inner join cat as c on cat_alt_manufacturer.ID_src=c.id_tof
      ".$sJoin."

   where 1=1
    ".$sWhere;

	return $sSql;
}
?>