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

	$sSql="	select cm.*,c.title as cat_title "
	.$sField.
	"from cat_model as cm 
	left join cat c on c.id_mfa=cm.id_mfa
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>