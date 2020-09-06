<?
function SqlPermissionActionCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pa.id='{$aData['id']}'";
	}

	$sSql="select pa.*, pd.id as deny, pa.action as pa_action
		   from permission_action pa
		   left join permission_deny pd on (pa.action=pd.action and pd.id_user='".$aData['id_user']."')
		   where 1=1 "
		   .$sWhere."
		   group by pa.id";
			
	return $sSql;
}
?>