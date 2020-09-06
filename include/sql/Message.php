<?
function SqlMessageCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and m.id='".$aData['id']."'";
	}

	if ($aData['join_user_from_to']) {
		$sJoin.="
			left join user as uf on (m.from=uf.login and uf.type_='customer')
			left join user as ut on (m.to=ut.login and ut.type_='customer')
		";
		$sField.="
			, uf.id as id_customer_from, ut.id as id_customer_to
			";
	}


	$sSql="select m.*
				".$sField."
			from message as m
				".$sJoin."
			where 1=1
				".$sWhere
	.$aData['order'];

	return $sSql;
}
?>