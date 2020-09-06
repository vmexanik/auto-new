<?
function SqlFormFormCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and f.id='{$aData['id']}'";
	}

	$sSql="select fi.*, f.*, count( fi.id ) AS item_count
		from form AS f
			left join form_item AS fi ON f.id = fi.id_form
		where 1=1 
		".$sWhere."
		group by f.id";

	return $sSql;
}
?>