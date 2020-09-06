<?
function SqlPriceImportCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pim.id='".$aData['id']."'";
	}
	
	if ($aData['id_user']) {
		$sWhere.=" and pim.id_user='".$aData['id_user']."'";
	}
	
	if ($aData['id_user_with_1']) {
		$sWhere.=" and (pim.id_user='".$aData['id_user_with_1']."' or pim.id_user='1')";
	}
	
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} 

	if ($aData['join']) {
		$sJoin=$aData['join'];
	}
	
	$sSql="	select pim.*
	, concat(ifnull(part_rus,''),' ',ifnull(part_eng,'')) as part_name , up.name as up_name
	".$sField." 
	from price_import as pim
	inner join user_provider as up on pim.id_provider=up.id_user
	".$sJoin." 
	where 1=1 
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>