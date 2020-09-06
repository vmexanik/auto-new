<?
function SqlOfficeWorkerCall($aData)
{
	$sWhere.=$aData['$sWhere'];

	if ($aData['id']) 
		$sWhere.=" and ow.id='{$aData['id']}'";

	if ($aData['id_office']) 
		$sWhere.=" and ow.id_office='{$aData['id_office']}'";

	if ($aData['position']) 
		$sWhere.=" and ow.position='{$aData['position']}'";
		
	$sSql="select ow.*, um.name as name
		,ifnull(um.phone,'') as phone
		,ifnull(um.icq,'') as icq
		from office_worker as ow
		inner join user_manager as um on (um.id_user=ow.id_user)
		where 1=1
		".$sWhere."
		group by ow.id";

	return $sSql;
}
?>