<?
function SqlOptiCatalogModelCall($aData) {
	
	if ($aData['id_make']) 
	{
		$sWhere.=" and cat.id = ".$aData['id_make'];
	}
	else 
	{
		$sWhere.=" and 1=0";
	}
	
	if ($aData['id_model']) 
	{
		$sWhere.=" and m.ID_src = ".$aData['id_model'];
	}
	if (1) {
		$sJoin=" inner join cat_model as cm on m.ID_src=cm.tof_mod_id and cm.visible=1";
	}
	
	$sSql="select coalesce(cm.name,m.Name) name,m.ID_src mod_id,m.ID_src id
		,concat(substr(m.DateStart,4,4),substr(m.DateStart,1,2)) mod_pcon_start
		,concat(substr(m.DateEnd,4,4),substr(m.DateEnd,1,2)) mod_pcon_end
		,cat.id_tof mod_mfa_id
		,substr(m.DateStart,1,2) as month_start, substr(m.DateStart,4,4) as year_start
		,substr(m.DateEnd,1,2) as month_end, substr(m.DateEnd,4,4) as year_end
		from cat
		inner join ".DB_OCAT."cat_alt_manufacturer man on man.ID_src=cat.id_tof
		inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
		".$sJoin."
 	where 1=1
    ".$sWhere;

	return $sSql;
}
?>