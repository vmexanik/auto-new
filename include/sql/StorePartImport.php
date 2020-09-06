<?
function SqlStorePartImportCall($aData) {
	
	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and spi.visible=1";
	}
	
	if ($aData['code']) 
	{
		$sWhere.=" and spi.code='".$aData['code']."'";
	}
	
	if ($aData['code_name']) 
	{
		$sWhere.=" and spi.code_name='".$aData['code_name']."'";
	}
	
	if ($aData['id_store_part']) 
	{
		$aData['id_store_part']=ltrim(trim($aData['id_store_part']),"ST");
		$sWhere.=" and spi.id='".$aData['id_store_part']."'";
	}
	
	if ($aData['id_store']) 
	{
		$sWhere.=" and spi.id_store='".$aData['id_store']."'";
	}
		
	$sSql="select spi.*, s.name as s_name
			 from store_part_import as spi
			 inner join store as s on spi.id_store=s.id
			".$sJoin."
			where 1=1
			".$sWhere;

	return $sSql;
}
?>