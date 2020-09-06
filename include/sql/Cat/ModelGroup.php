<?
function SqlCatModelGroupCall($aData) {

	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere,$aData,"id","cmg");

	if ($aData['visible']!="") 
	{
		$sWhere.=" and cmg.visible='".$aData['visible']."'";
	} 

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} 

	$sSql="	select cmg.* ,c.title as brand, c.name as cat_name "
	.$sField.
	"from cat_model_group as cmg 
	 left join cat as c on c.id=cmg.id_make
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>