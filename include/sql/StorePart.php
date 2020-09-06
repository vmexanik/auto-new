<?
function SqlStorePartCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and sp.visible=1";
	}
	
	if ($aData['code']) 
	{
		$sWhere.=" and cp.code='".$aData['code']."'";
	}
	
	if ($aData['item_code']) 
	{
		$sWhere.=" and cp.item_code='".$aData['item_code']."'";
	}
	
	if ($aData['code_name']) 
	{
		$sWhere.=" and sp.code_name='".$aData['code_name']."'";
	}
	
	if ($aData['id_store_part']) 
	{
		$aData['id_store_part']=ltrim(trim($aData['id_store_part']),"ST");
		$sWhere.=" and sp.id='".$aData['id_store_part']."'";
	}
	
	if ($aData['id_store']) 
	{
		$sWhere.=" and sp.id_store='".$aData['id_store']."'";
	}
	
	if ($aData['group']=="item_code") 
	{
		$sField.=" , sum(sp.number) as numbersum ";
		$sGroup=" group by item_code";
	} else {
		$sJoin.=" left join store_provider as spr on sp.id_store=spr.id_store";
		$sGroup=" group by sp.id";
	}
		
	$sSql="select sp.*, cp.item_code, cp.code, cp.pref, cp.name as cp_name, s.name as s_name
			".$sField."
			 from store_part as sp
			 inner join cat_part as cp on sp.id_cat_part=cp.id
			 inner join store as s on sp.id_store=s.id
			".$sJoin."
			where 1=1
			".$sWhere
			.$sGroup;

	return $sSql;
}
?>