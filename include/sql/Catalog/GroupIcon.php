<?
function SqlCatalogGroupIconCall($aData)
{
	if(!$aData['aIdIcon']) $aData['aIdIcon']=array();
	$inCode = "'".implode("','",$aData['aIdIcon'])."'";
	
	$sWhere.=" and cgi.id in (".$inCode.")";
	
	$sSql="select cgi.id , cgi.image 
	 from cat_group_icon as cgi
	 where 1=1
	 ".$sWhere;

	return $sSql;
}
?>
