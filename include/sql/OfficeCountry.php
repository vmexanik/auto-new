<?
function SqlOfficeCountryCall($aData) {

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

	$sSql="	select oc.* "
	.$sField.
	"from office_country as oc 
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>