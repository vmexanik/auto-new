<?
function SqlCartReceiptCall($aData) {

	if ($aData['id_cart_sending'])
	{
		$sWhere.=" and cr.id_cart_sending='".$aData['id_cart_sending']."'";
	}

	if ($aData['visible']!="")
	{
		$sWhere.=" and cr.visible='".$aData['visible']."'";
	}

	if ($aData['group_id_cart'])
	{
		$sField=", sum(cr.quantity) as quantity";
		$sGroup=" group by cr.id_cart";
	}
	
	if ($aData['group_id_cart_sending'])
	{
		$sField=", sum(cr.quantity*cr.price) as sum_price";
		$sGroup=" group by cr.id_cart_sending";
	}


	$sSql="select c.*, cr.quantity, cr.id as cr_id, cr.id_cart, ifnull(cp.name,'') as name
	, ifnull(csc.id_cart_sending,'') as csc_id_cart_sending
	        ".$sField."
			 from cart_receipt as cr
			 inner join cart as c on cr.id_cart=c.id
			 left join cat_part as cp on c.item_code=cp.item_code
			 left join cart_sticker_confirm as csc on cr.id_cart=csc.id_cart
			".$sJoin."
			where 1=1
			".$sWhere.$sGroup
	;
	return $sSql;
}
?>