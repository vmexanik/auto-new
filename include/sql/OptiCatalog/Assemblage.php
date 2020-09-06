<?
function SqlOptiCatalogAssemblageCall($aData) {

	if (!$aData['id_model_detail']) return "select null";
	
	Db::SetWhere($sWhere, $aData, 'level', 't');
	Db::SetWhere($sWhere, $aData, 'id_parent', 't');

	if ($aData['join_cat_tree']){
		$sJoin.=" inner join cat_tree ct on ct.id=t.ID_tree and ct.visible=1";
		$sField.=",ct.name";
	}

	$sSql="(select t.ID_src as id,
       t.Level+1 str_level,
       t.Sort str_sort,
       0 expand,
       CONCAT(UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as data,
       t.ID_parent str_id_parent,
       0 color
	   ".$sField."
  from ".DB_OCAT."cat_alt_tree t
  ".$sJoin."
  /*JOIN ".DB_OCAT."cat_alt_link_typ_str lts ON lts.lts_str_id = t.ID_src AND lts.lts_typ_id ='".$aData['id_model_detail']."'*/
  INNER JOIN (
	SELECT link.ID_tree
	FROM ".DB_OCAT."`cat_alt_link_typ_art` ltyp
	INNER JOIN ".DB_OCAT."cat_alt_link_str_grp link ON link.`ID_grp` = ltyp.`ID_grp`
	INNER JOIN ".DB_OCAT."cat_alt_types t on ltyp.`ID_typ`=t.ID_typ and  t.ID_src='".$aData['id_model_detail']."'
	GROUP BY link.ID_tree
	)groups ON t.ID_tree = groups.ID_tree
   where t.Level > 0 
   ".$sWhere."
   order by t.Name)";
	
	$sSql.=" union
	(select t.ID_src as id,
       t.Level+1 str_level,
       t.Sort str_sort,
       0 expand,
       t.Name as data,
       t.ID_parent str_id_parent,
       ctl.id color
	   ".$sField."
  from ".DB_OCAT."cat_alt_tree t
   inner join cat_tree_type_link as ctl on ctl.id_typ='".$aData['id_model_detail']."' and t.ID_src =ctl.id_tree
   where t.Level > 0
   ".$sWhere."
   order by t.Name)";
	
	return $sSql;
}
// for full table ".DB_TOF."tof__link_la_typ
// and substr(lat_ctm,185,1)=1
?>