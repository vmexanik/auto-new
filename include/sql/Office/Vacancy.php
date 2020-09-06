<?
function SqlOfficeVacancyCall($aData)
{
	$sWhere.=$aData['$sWhere'];

	if ($aData['visible'])
		$sWhere.=" and ov.visible='".$aData['visible']."'";

	if ($aData['id']) 
		$sWhere.=" and ov.id='{$aData['id']}'";

	if ($aData['id_office']) 
		$sWhere.=" and ov.id_office='{$aData['id_office']}'";

	if ($aData['name']) 
		$sWhere.=" and ov.name='{$aData['name']}'";
		
	if ($aData['id_office_city']) 
		$sWhere.=" and oc.id='{$aData['id_office_city']}'";

	$sSql="select ov.*, oc.name as city
		from office_vacancy as ov
		inner join office as o on o.id=ov.id_office
		inner join office_city as oc on oc.id=o.id_office_city
		where 1=1
		".$sWhere."
		group by ov.id";

	return $sSql;
}
?>