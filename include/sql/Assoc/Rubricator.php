<?
function SqlAssocRubricatorCall($aData)
{
    $sWhere.=$aData['where'];

    Db::SetWhere($sWhere, $aData, 'id', 'r');
    Db::SetWhere($sWhere, $aData, 'visible', 'r');
    Db::SetWhere($sWhere, $aData, 'is_mainpage', 'r');
    Db::SetWhere($sWhere, $aData, 'is_menu_visible', 'r');

    $sSql="select r.id, concat('[',r.url,']',' ',r.name) as name
			from rubricator r
			".$sJoin."
			where 1=1 ".$sWhere;

    return $sSql;
}