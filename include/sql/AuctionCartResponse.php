<?
function SqlAuctionCartResponseCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id'])
	{
		$sWhere.=" and acr.id='".$aData['id']."'";
	}

	if ($aData['id_auction'])
	{
		$sWhere.=" and acr.id_auction='".$aData['id_auction']."'";
	}

	if ($aData['id_user_provider'])
	{
		$sWhere.=" and acr.id_user_provider='".$aData['id_user_provider']."'";
	}
	
	if ($aData['is_order'])
	{
		$sWhere.=" and acr.is_order='".$aData['is_order']."'";
	}
	
	if ($aData['pref'])
	{
		$sWhere.=" and acr.pref='".$aData['pref']."'";
	}
	
	if ($aData['not_refused'])
	{
		$sWhere.=" and c.order_status<>'refused'";
	}

	$sSql=" select acr.*, up.name as up_name, up.code_currency_auction
		, ifnull(c.number, '') as c_quantity, ifnull(c.price, '') as c_price, ifnull(c.price_original, '') as c_price_original
		, ifnull(c.provider_name, '') as c_provider_name, ifnull(u.login, '') as u_login, ifnull(c.sign,'') as sign
		".$sField."
		from auction_cart_response as acr
		inner join user_provider as up on up.id_user=acr.id_user_provider
		left join auction_cart as ac on acr.id_cart=ac.id_cart
		left join cart as c on ac.id_cart=c.id
		left join user as u on c.id_user=u.id
		".$sJoin."
		 where 1=1 "
	.$sWhere
	;


	return $sSql;
}
?>