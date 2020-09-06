<?
function SqlDirectoryCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and d.id='{$aData['id']}'";
	}

	$sSql="select d.* ,d.visible as visible, dc.name as directory_category_name
			from directory d
     		inner join directory_category as dc on d.id_directory_category=dc.id
			where 1=1 ".$sWhere."
			group by d.id";

	return $sSql;
}
?>