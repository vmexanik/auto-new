<?
function SqlPartStickerCall($aData) {


	//$sWhere=$aData['where'];
		
	if ($aData['aauto_brand']) {
		$sField.=", ab.name as aauto_brand_name";
		$sJoin.=" left join aauto_brand as ab on a.id_aauto_brand=ab.id ";
	}
	
	if ($aData['code']) $sWhere.=" and c.code='".$aData['code']."'";
	
	if (1) $sWhere.=" and csc.post_date>=date_format(now(), '%Y-%m-%d') and csc.post_date<date_format(now()+ interval 1 day, '%Y-%m-%d')";

	$sSql="select csc.id, csc.id_cart, c.code, c.name, c.number
			 from cart_sticker_confirm csc
			 inner join cart as c on c.id=csc.id_cart
			".$sJoin."
			where 1=1
			".$sWhere;

	return $sSql;
}
?>