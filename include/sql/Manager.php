<?
function SqlManagerCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and u.id='{$aData['id']}'";
	}
	if ($aData['login']) {
		$sWhere.=" and u.login='{$aData['login']}'";
	}
	if ($aData['id_array']) {
		$sWhere.=" and u.id in (".implode(',',$aData['id_array']).")";
	}

	$sSql="select u.*,um.*
			from user_manager um
			inner join user as u on u.id=um.id_user
			where 1=1
				 ".$sWhere."
			group by u.id";

	return $sSql;
}
?>