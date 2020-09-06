<?php
function SqlCatalogCatPrefCall($aData) {

	$sWhere.=$aData['where'];
	if ($aData['childs']) $sIdCategory="";
	else $sIdCategory="and pgp.id_price_group='".$aData['id_category']."' ";

	$sSql="select c.* from cat as c
	join price_group_assign as pgp on c.pref=pgp.pref
	and (c.title='".$aData['brand']."' or c.name='".$aData['brand']."')
	".$sIdCategory."
	where 1=1
	".$sWhere
	.$aData['order'];

	return $sSql;
}
?>