<?
function SqlCatCall($aData) {

	$sWhere.=$aData['where'];
	
	Db::SetWhere($sWhere,$aData,'id','c');
	Db::SetWhere($sWhere,$aData,'pref','c');
	Db::SetWhere($sWhere,$aData,'is_main','c');
	Db::SetWhere($sWhere,$aData,'is_brand','c');
	Db::SetWhere($sWhere,$aData,'visible','c');
	Db::SetWhere($sWhere,$aData,'id_tof','c');

	if ($aData['join']) {
		$sJoin .= " ".$aData['join'];
	}

	if ($aData['order']) {
		$sOrder.=" order by ".$aData['order'];
	}
	
	if ($aData['name']) {
		$sWhere.=" and (c.name='".mb_strtoupper($aData['name'],'utf-8')."' || c.name='".mb_strtolower($aData['name'],'utf-8')."')";
	}
	
	if ($aData['where'])
		$sWhere .= $aData['where'];
	
	$sSql="select c.*, cv.title virtual_title, cv.pref as virtual_pref
			from cat as c
			left join cat as cv on c.id_cat_virtual=cv.id and cv.is_cat_virtual
			".$sJoin."
			where 1=1
			".$sWhere."
			group by c.id
			".$sOrder;

	return $sSql;
}
?>