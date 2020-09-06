<?
function SqlAssocUserAccountLogCall($aData)
{
	$sWhere.=$aData['where'];

	$sSql="	select ual.id , ual.*, ual.id as custom_id, ualt.name as user_account_log_type_name
	from user_account_log as ual
	inner join user_account_log_type as ualt on ual.id_user_account_log_type=ualt.id
	where 1=1
	".$sWhere;

	return $sSql;
}
?>