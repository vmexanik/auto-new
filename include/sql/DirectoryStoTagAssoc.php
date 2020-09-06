<?
function SqlDirectoryStoTagAssocCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ds.id='{$aData['id']}'";
	}
	if ($aData['id_directory_sto']) {
		$sWhere.=" and dst.id_directory_sto='{$aData['id_directory_sto']}'";
	}

	if ($aData['order']) {
		$sOrder.=$aData['order'];
	}

	$sSql="select dt.name as id ,dst.*
			from directory_sto_tag as dst
			inner join directory_tag as dt on dst.id_directory_tag=dt.id
			where 1=1
			".$sWhere."
			".$sOrder."
			".$sLimit;

	return $sSql;
}
?>