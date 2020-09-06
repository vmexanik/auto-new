<?
function SqlCartPackingCall($aData) {

	if ($aData['id_cart_packing_box'])
	{
		$sWhere.=" and cp.id_cart_packing_box='".$aData['id_cart_packing_box']."'";
	}

	if ($aData['visible']!="")
	{
		$sWhere.=" and cp.visible='".$aData['visible']."'";
	}

	if ($aData['group_id_cart'])
	{
		$sField=", sum(quantity) as quantity";
		$sGroup=" group by cp.id_cart";
	}
	
	if ($aData['group_id_cart_packing_box'])
	{
		$sField=", sum(cp.quantity*cp.price) as sum_price";
		$sGroup=" group by cp.id_cart_packing_box";
	}


	$sSql="select c.* , cp.id_cart, cp.quantity, cp.id as id_cart_packing, cp.price as cp_price
	       , ifnull(csc.id_cart_sending,-1) as id_cart_sending
	        ".$sField."
			 from cart_packing as cp
			 inner join cart as c on cp.id_cart=c.id
			 left join cart_sticker_confirm as csc on cp.id_cart=csc.id_cart
			".$sJoin."
			where 1=1
			".$sWhere.$sGroup
	;
	//inner join cart_packing_box as cpb on cp.id_cart_packing_box=cpb.id
	return $sSql;
}
?>