<?
function SqlCartPackingBoxCall($aData) {


	if ($aData['id_cart_packing_box']!="")
	{
		$sWhere.=" and cpb.id='".$aData['id_cart_packing_box']."'";
	}

	if ($aData['id_cart_sending']!="")
	{
		$sWhere.=" and cpb.id_cart_sending='".$aData['id_cart_sending']."'";
	}
	
	if ($aData['id_cart_store']!="")
	{
		$sWhere.=" and cpb.id_cart_store='".$aData['id_cart_store']."'";
	}

	if ($aData['id_cart']!="")
	{
		$sWhere.=" and cp.id_cart='".$aData['id_cart']."'";
		if ($aData['visible']!="")
		{
			$sWhere.=" and cp.visible='".$aData['visible']."'";
		}
	}

	$sSql="select cpb.*, sum(cp.price*cp.quantity) as price
			 from cart_packing_box as cpb
			 left join cart_packing as cp on cpb.id=cp.id_cart_packing_box and cp.visible=1
			".$sJoin."
			where 1=1
			".$sWhere."
			 group by cpb.id ";
	return $sSql;
}
?>