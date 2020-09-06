<?
function SqlAssocProviderCall($aData)
{
	$sSql="
	select lower(u.login), u.id
	from user as u
	where u.type_='provider'
		".$sWhere;

	return $sSql;
}
?>