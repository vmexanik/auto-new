<?
function SqlSitemapLinksCall($aData) {

	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere,$aData,'id','sl');
	Db::SetWhere($sWhere,$aData,'visible','sl');
	Db::SetWhere($sWhere,$aData,'url','sl');

	if ($aData['order']) {
		$sOrder.=" order by ".$aData['order'];
	}

	$sSql="select sl.*
			from sitemap_links as sl
			where 1=1
			".$sWhere."
			group by sl.id
			".$sOrder;

	return $sSql;
}
?>