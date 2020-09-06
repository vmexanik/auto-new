<?
function SqlAuctionCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id'])
	{
		$sWhere.=" and a.id='{$aData['id']}'";
	}

	if ($aData["sSearchType"]=="file" and $aData["type_"])
	{
		$sField.=" , af.name as af_name , af.pref as af_pref, af.post_date as af_post_date
		, af.part_cnt as af_part_cnt, af.part_sum as af_part_sum, af.rise_in_price as rise_in_price
		, af.id_user_provider as id_user_provider, af.id as af_id , af.is_send as af_is_send";
		$sJoin.=" inner join auction_file as af on af.id_auction=a.id and af.type_='".$aData["type_"]."'";
		//$sJoin.=" inner join cat as c on af.pref=c.pref ";
		$sWhere.=" and af.visible=1";
		

		if ($aData["provider_pref"])
		{
			$sJoin.="inner join provider_pref as pp on af.pref=pp.pref
			inner join user_provider as up on pp.id_user_provider=up.id_user and a.id_provider_region=up.id_provider_region";
			$sField.=" , pp.mail_to as mail_to, pp.name_to as name_to, pp.subject as subject
			, pp.id_user_provider as pp_id_user_provider";
		}
		
		if ($aData['is_send']) 
		{
			$sWhere.=" and af.is_send=1";
		}
		
	}

	$sSql=" select a.*, as.name as as_name, pr.name as pr_name
		".$sField." 
		 from auction as a 
		 inner join auction_status as `as` on as.id=a.id_auction_status  
		 inner join provider_region as pr on a.id_provider_region=pr.id  
		".$sJoin."
		 where 1=1 
		".$sWhere
	;

	return $sSql;
}
?>