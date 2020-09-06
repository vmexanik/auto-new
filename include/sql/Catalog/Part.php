<?
function SqlCatalogPartCall($aData) {

	//	$inID=Db::GetOne("select group_concat(lgs_ga_id)
	//           			 from ".DB_TOF."tof__link_ga_str
	//	  				where lgs_str_id = ".$aData['id_part']);

	//	if ($aData['id_make'])
	//	{
	//		$sWhere="and mod_mfa_id = ".$aData['id_make'];
	//	}

	$sSql="select lat_sup_id sup_id, ga_nr
from ".DB_TOF."tof__link_la_typ
join ".DB_TOF."tof__generic_articles on ga_id = lat_ga_id 
where 1 = 1 and lat_typ_id = ".$aData['id_model_detail']." 
    and lat_ga_id in (Select lgs_ga_id
           			 from ".DB_TOF."tof__link_ga_str
	  				where lgs_str_id = ".$aData['id_part'].")
    ".$sWhere;
	return $sSql;


}
?>