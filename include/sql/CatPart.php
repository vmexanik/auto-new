<?
function SqlCatPartCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and cp.id='{$aData['id']}'";
	}

	if ($aData['pref']) {
		$sWhere.=" and cp.pref='{$aData['pref']}'";
	}

	if ($aData['code']) {
		$sWhere.=" and cp.code='{$aData['code']}'";
	}
	
	if ($aData['item_code']) {
		$sWhere.=" and cp.item_code='".$aData['item_code']."'";
	}
	
	if ($aData['weight_log']) {
		$sField=" , cpw.weight as cpw_weight , cpw.post_date as cpw_post_date, cpw.name_rus as cpw_name_rus
					, cpw.comment as cpw_comment
					, u.login as u_login ";
		$sJoin=" inner join cat_part_weight as cpw on cp.id=cpw.id_cat_part
				 inner join user as u on cpw.id_user=u.id
		";

		if ($aData['comment']) {
			$sWhere.=" and cpw.comment like '%".$aData['comment']."%'";
		}
	}

	$sSql="select cp.*, cp.id as id_cat_part, c.title as brand, uu.login as cco_manager,
	    uum.name as cco_manager_name, a.name as cco_admin_name, a.login as cco_admin
		".$sField."
	from cat_part as cp
		left join cat c on c.pref = cp.pref
		left join user uu on uu.id = cp.is_checked_code_ok_manager
		left join user_manager uum on uu.id = uum.id_user
		left join admin a on a.id = cp.is_checked_code_ok_admin		    
		".$sJoin."
	where 1=1
		".$sWhere
	;

	return $sSql;
}
?>