<?
function SqlOfficeRegionCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ofr.id='".$aData['id']."'";
	}

	if ($aData['visible']!="") 
	{
		$sWhere.=" and ofr.visible='".$aData['visible']."'";
	} 

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} 

	$sSql="	select ofr.* "
	.$sField.
	"from office_region as ofr 
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>