<?
function SqlPriceQueueCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pq.id='".$aData['id']."'";
	} else {
		if ($aData['all']) {
			$sWhere.=" ";
		} else {
			$sWhere.=" and pq.visible=1";
		}
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}
	
	if ($aData['is_processed']) {
		$sWhere.=" and pq.is_processed='".$aData['is_processed']."'";
	}
	
	if ($aData['id_price_profile']) {
		$sWhere.=" and pq.id_price_profile='".$aData['id_price_profile']."'";
	}
	
	if ($aData['id_user_provider']) {
		$sWhere.=" and pq.id_user_provider='".$aData['id_user_provider']."'";
	}

	$sSql="	select pq.*
	, ifnull(pp.name,'') as pp_name
	, ifnull(up.name,'') as up_name
	".$sField."
	from price_queue as pq 
	left join price_profile as pp on pq.id_price_profile=pp.id
	left join user_provider as up on pq.id_user_provider=up.id_user
	where 1=1
	".$sWhere
	." ".$sOrder;

	return $sSql;
}
?>