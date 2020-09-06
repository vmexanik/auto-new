<?
function SqlCatBrandCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and c.id='{$aData['id']}'";
	}

	if ($aData['order']) {
		$sOrder.=" order by {$aData['order']}";
	}

	if ($aData['id_cat_make_category']) {
		$sWhere .= " and ct.id_cat_make_category=".$aData['id_cat_make_category'];
	}
	
	if ($aData['pref']) {
		$sWhere .= " and c.pref='".$aData['pref']."'";
	}

	$sSql="select c.* ,if(c.image!='' and c.is_use_own_logo=1,c.image,if(c.image_tecdoc<>'',concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , c.image_tecdoc),c.image)) as image
			from cat as c
			-- inner join cat_type as ct on ct.id_cat = c.id
			".$sJoin."
			where 1=1
			".$sWhere."
			group by c.id 
			".$sOrder;

	return $sSql;
}
?>