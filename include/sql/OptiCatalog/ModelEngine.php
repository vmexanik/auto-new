<?
function SqlOptiCatalogModelEngineCall($aData) {
	
	if ($aData['id_model']) 
	{
		$sWhere.="and m.ID_src = ".$aData['id_model'];
	}
	elseif ($aData["code"] && $aData["art_id"]) 
	{
		$sJoin=" inner join ".DB_TOF."tof__link_la_typ_view on typ_id = lat_typ_id";
		$sWhere.=" and art_article_nr = '".$aData["code"]."' and art_id='".$aData["art_id"]."'";
	}
	else 
	{
		$sWhere="and 1=0";
	}
	
	if ($aData['id_model_detail']) 
	{
		$sWhere.=" and t.ID_src = ".$aData['id_model_detail'];
	}
	
	if ($aData['year']) 
	{
		$sWhere.=" and t.DateStart<=".$aData['year']."01"
		." and ifnull(t.DateEnd,999999)>=".$aData['year']."01";
	}
	
	
	if ($aData['volume']) 
	{
		$dVolume=str_replace(",",".",$aData['volume']);
		if ($dVolume<=100) {
			$sWhere.=" and round(t.CCM,-2)="
			.($dVolume*1000);
		} else {
			$sWhere.=" and round(t.CCM,-2)="
			.round($dVolume,-2);
		}
		
		//$sWhere.=" and substring(ifnull(tyc_ccm, typ_ccm),1,2)="
		//.substr(str_replace(array(",","."),"",$aData['volume']),0,2);
	}
	
	if ($aData['fuel']) 
	{
		$sWhere.=" and t.Fuel='".$aData['fuel']."'";
	}
		
		
	$sSql="select t.Name name,
         t.CCM ccm  
		, cat.id as id_make
		, m.ID_src as id_model
		, t.ID_src as id_model_detail
		,t.DateStart pcon_start   
         ,t.DateEnd pcon_end
         , substr(t.DateStart,5,2) as month_start
         , substr(t.DateStart,1,4) as year_start
		, substr(t.DateEnd,5,2) as month_end
		, substr(t.DateEnd,1,4) as year_end  
    from ".DB_OCAT."cat_alt_types t
    inner join ".DB_OCAT."cat_alt_models m on t.ID_mod = m.ID_mod
	inner join ".DB_OCAT."cat_alt_manufacturer man on man.ID_mfa=t.ID_mfa
    inner join cat as cat on man.ID_src=cat.id_tof
      ".$sJoin."

   where 1=1 
    ".$sWhere;

	return $sSql;
}
?>