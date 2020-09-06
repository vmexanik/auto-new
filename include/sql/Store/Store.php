<?
function SqlStoreStoreCall($aData) {

	$sWhere.=$aData['where'];

	Db::SetWhere($sWhere,$aData,'id','s');
	Db::SetWhere($sWhere,$aData,'code','s');
	Db::SetWhere($sWhere,$aData,'name','s');
	Db::SetWhere($sWhere,$aData,'is_virtual','s');
	Db::SetWhere($sWhere,$aData,'visible','s');
	Db::SetWhere($sWhere,$aData,'is_return','s');
	Db::SetWhere($sWhere,$aData,'is_sale','s');
	Db::SetWhere($sWhere,$aData,'provider','up','name');

	$sSql="select s.*, up.name as provider
	from store as s
	inner join user_provider up on s.id_provider=up.id_user
	where 1=1
	".$sWhere;

	return $sSql;
}
?>