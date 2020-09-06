<?
function SqlOptiCatalogModelInfoCall($aData) {
	
	if ($aData['id_model_detail']) 
	{
		$sWhere.=" and cat_alt_types.ID_src = ".$aData['id_model_detail'];
	}
	else 
	{
		$sWhere=" and 1=0";
	}
	
	$sSql="select 
         
		 cat_alt_types.Description type_auto,
         substr(cat_alt_types.DateStart,1,4) model_year_from,
         substr(cat_alt_types.DateEnd,1,4) model_year_to,
         LEFT(KwHp, LOCATE('/', KwHp)-1) power_kw,     
		 SUBSTR(KwHp, LOCATE('/', KwHp)+1) power_hp,
         CCM as tech_engine_capacity,   
         Body body_type,   
         Drive drive_type,   
         Fuel fuel_type,   
         Engines engine_type,   
		 Doors door,
         '' cylinder
    from ".DB_OCAT."cat_alt_types
   where 1=1 
   ".$sWhere;

	return $sSql;
}
?>