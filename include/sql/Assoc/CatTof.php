<?
function SqlAssocCatTofCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		//$sWhere.=" and c.visible=1";
	}
	
	Db::SetWhere($sWhere,$aData,'id','c');
	Db::SetWhere($sWhere,$aData,'pref','c');
	Db::SetWhere($sWhere,$aData,'is_main','c');
	Db::SetWhere($sWhere,$aData,'is_brand','c');
	Db::SetWhere($sWhere,$aData,'is_vin_brand','c');

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by c.name ";
	}

	if ($aData['multiple']) {
		$sField.=", c.image , c.name as c_name ";
	}
	
	$sSql="select c.id_tof, c.title as name 
	".$sField."
	from cat as c
	".$sJoin."
	where 1=1
	".$sWhere."
	group by c.id_tof
	".$sOrder;
	
	return $sSql;
}
?>