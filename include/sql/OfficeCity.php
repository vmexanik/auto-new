<?
function SqlOfficeCityCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and oc.id='".$aData['id']."'";
	}

	if ($aData['visible']!="") 
	{
		$sWhere.=" and oc.visible='".$aData['visible']."'";
	} 

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} 

	$sSql="	select oc.* 
	,ifnull(ofc.name,'') as office_country_name
	,ifnull(ofr.name,'') as office_region_name "
	.$sField.
	"from office_city as oc
	left join office_country as ofc on oc.id_office_country=ofc.id
	left join office_region as ofr on oc.id_office_region=ofr.id
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>