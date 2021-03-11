<?
function SqlCatalogPartRelatedCall($aData) {
    $sWhere=$aData['where'];
    if ($aData['id'])
    {
        $sWhere.=" and cc.id=".$aData['id'];
    }
    if ($aData['pref'])
    {
        $sWhere.=" and cc.pref='".$aData['pref']."' or cc.pref_related='".$aData['pref']."'";
    }

    if ($aData['aCode'])
    {
        $sWhere.=" and (cc.code in ('".implode("','",$aData['aCode'])."') or cc.code_related in ('".implode("','",$aData['aCode'])."'))";
    }

    if ($aData['join'] == 1) {
        $sJoin = 'join cat as ccrs on cc.pref = ccrs.pref join cat as r on cc.pref_related = r.pref';
        $sFields = ', ccrs.title as brand, r.title as related_brand';
    }

    $sSql="select cc.*, cc.post_date+0 as post_time".$sFields."
	from related_products as cc
	".$sJoin." 
	where 1=1 "
        .$sWhere;

    return $sSql;
}
?>