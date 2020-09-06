<?
function SqlOfficeCall($aData)
{
	$sWhere.=$aData['$sWhere'];

	if ($aData['id'])
		$sWhere.=" and o.id='".$aData['id']."'";

	if ($aData['id_office_city'])
		$sWhere.=" and oc.id='".$aData['id_office_city']."'";

	if ($aData['id_office_region'])
		$sWhere.=" and ofr.id='".$aData['id_office_region']."'";

	$sSql="select distinct o.*, oc.name as city
			, oi.name as office_image_name
			, oi.name_small as office_image_name_small
			from office as o
			inner join office_city as oc on oc.id=o.id_office_city
			left join office_image as oi on (oi.id_office=o.id)
			left join office_region as ofr on ofr.id=oc.id_office_region
			where 1=1
			".$sWhere."
			group by o.id";

	return $sSql;
}
?>