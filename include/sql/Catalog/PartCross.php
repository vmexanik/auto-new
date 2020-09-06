<?
function SqlCatalogPartCrossCall($aData) {
	$sWhere=$aData['where'];
	if ($aData['id'])
	{
		$sWhere.=" and cc.id=".$aData['id'];
	}
	
	if ($aData['pref'])
	{
		$sWhere.=" and cc.pref='".$aData['pref']."'";
	}
	
	if ($aData['source'])
	{
		$sWhere.=" and cc.source='".$aData['source']."'";
	}
	
	if ($aData['aCode']) 
	{
		$sWhere.=" and (cc.code in ('".implode("','",$aData['aCode'])."') or cc.code_crs in ('".implode("','",$aData['aCode'])."'))";
	}

	if ($aData['join'] == 1) {
		$sJoin = 'join cat as ccrs on cc.pref_crs=ccrs.pref 
				left join user_manager um on um.id_user=cc.id_manager 
				left join user u on u.id=cc.id_manager';
		$sFields = ', ccrs.title as brand, um.name manager_name,u.login manager_login ';
	} 
		
	$sSql="select cc.*, concat(pref_crs,'_',code_crs) as item_code,cc.post_date+0 as post_time".$sFields."
	from cat_cross as cc
	".$sJoin." 
	where 1=1 "
	.$sWhere;
	
	//join cat as c on cc.pref==c.pref
	//join cat1 as c1 on cc.pref_crs==c1.pref
	
	return $sSql;
}
?>