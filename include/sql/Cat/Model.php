<?
function SqlCatModelCall($aData) {

	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere,$aData,"id","cm");

	if ($aData['visible']!="") 
	{
		$sWhere.=" and cm.visible='".$aData['visible']."'";
	} 

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} 

	$sSql="	select cm.* "
	.$sField.
	"from cat_model as cm 
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>