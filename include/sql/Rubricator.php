<?
function SqlRubricatorCall($aData) {

	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere, $aData, 'id', 'r');
	Db::SetWhere($sWhere, $aData, 'visible', 'r');
	Db::SetWhere($sWhere, $aData, 'is_mainpage', 'r');
	Db::SetWhere($sWhere, $aData, 'is_menu_visible', 'r');

	if($aData['field']){
		$sField=$aData['field'];
	}else{
		$sField="r.*";
	}
	$sSql="select ".$sField."
			from rubricator r
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>