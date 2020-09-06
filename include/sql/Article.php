<?
function SqlArticleCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and a.id='{$aData['id']}'";
	}

	$sSql="select a. * , ac.name as article_category_name
			from article as a
     		inner join article_category as ac on a.id_article_category = ac.id
			where 1=1 ".$sWhere."
			group by a.id";

	return $sSql;
}
?>