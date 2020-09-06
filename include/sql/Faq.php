<?
function SqlFAQCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and f.id='{$aData['id']}'";
	}

	$sSql="select f. * , f.visible as visible, fc.name as faq_category_name
			from faq as f
     		inner join faq_category as fc on f.id_faq_category = fc.id
			where 1=1 ".$sWhere."
			group by f.id";

	return $sSql;
}
?>