<?
function SqlRatingLogCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and rl.id='".$aData['id']."'";
	}

	$sSql="select rl.*
				,u.login as user_login, um.login as manager_login
				,r.name as rating_name
			from rating_log as rl
			inner join rating as r on (rl.num_rating=r.num and rl.section=r.section)
			inner join user as u on rl.ref_id=u.id
			left join user as um on rl.id_user_manager=um.id
			where 1=1
			".$sWhere."
			";

	return $sSql;
}
?>