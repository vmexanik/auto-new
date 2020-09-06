<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryPriceCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id_user']) {
		$sWhere.=" and p.id_user='{$aData['id_user']}'";//.Auth::$aUser['id'];
	}

	$sSql="select concat(ifnull(part_rus,''),' ',ifnull(part_eng,'')) as part_name , up.name as up_name, p.*
			from price as p
			inner join user_provider as up on p.id_provider=up.id_user
			where 1=1".$sWhere;

	return $sSql;
}
?>