<?
function SqlCartSendingCall($aData) {
	
	if ($aData['visible']!="") 
	{
		$sWhere.=" and cs.visible='".$aData['visible']."'";
	} 
	
	$sSql="select cs.*, prw.name as prw_name
			 from cart_sending as cs
			 inner join provider_region_way as prw on cs.id_provider_region_way=prw.id
			".$sJoin."
			where 1=1
			".$sWhere;
	return $sSql;
}
?>